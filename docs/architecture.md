# 시스템 구조

---

## 전체 그림

```
                            ┌────────────────────────────────────┐
    Browser ──── HTTP ─────▶│  Apache + PHP  (웹 · 뷰 계층)       │
        ▲                   │                                    │
        │   조각 HTML        │  index · problem · userinfo        │
        └───────────────────│  quest · board · category · status │
                            └────────────────┬───────────────────┘
                                             │  SQL
                                             ▼
                            ┌────────────────────────────────────┐
                            │       MariaDB  ( DB: jol )         │
                            │                                    │
                            │  HUSTOJ 원본 테이블                 │
                            │    users · problem · solution      │
                            │    contest · news · mail           │
                            │                                    │
                            │  AlgoWiki 추가 테이블 ★             │
                            │    uinfo    (EXP · 코인 · 티어)     │
                            │    quests   (퀘스트 정의)           │
                            │    progress (사용자별 진행도)        │
                            │    accept   (첫 정답 기록)          │
                            │    pinfo    (문제 통계)             │
                            │    tag · user_link                 │
                            └────────┬──────────────────┬────────┘
                                     ▲                  ▲
                       ①  정답 판정 시 │                  │ ② 매일/매주 00시
                            ┌────────┴────────┐   ┌─────┴──────────────┐
                            │ HUSTOJ judged   │   │ cron 스케줄러 (C++) │
                            │  (C++ 채점기)    │   │  daily_scheduler   │
                            │   └ quest_api ★ │   │  weekly_scheduler  │
                            └─────────────────┘   └────────────────────┘
```

★ = AlgoWiki가 직접 추가한 부분

**인프라** — AWS EC2 (Ubuntu) 단일 인스턴스, Apache + PHP + MariaDB + HUSTOJ judged.

---

## ① 채점기 훅 — `src/judge/quest_api/`

이 프로젝트에서 **가장 중요한 서버 사이드 결정**입니다.

### 왜 웹이 아니라 채점기인가

퀘스트 진행도를 갱신할 수 있는 자리는 두 곳입니다.

| 후보 | 문제점 |
|---|---|
| PHP에서 "제출 버튼" 눌렀을 때 | 채점 결과가 아직 안 나옴. 정답인지 모름 |
| PHP에서 "채점 현황" 볼 때 | **안 보면 갱신이 안 됨.** 폴링으로 때우면 중복·누락 발생 |

정답 여부를 **가장 먼저, 정확히 한 번** 아는 주체는 채점기입니다.
그래서 채점기가 결과를 기록하는 그 지점에 훅을 넣었습니다.

```cpp
void ac_api_process(MYSQL * conn, int solution_id);
```

### 처리 흐름

```
정답(AC) 판정
   │
   ▼
first_check()          ─── solution_id 로 user_id · problem_id 조회
   │                       accept 테이블에 이미 있으면 → 즉시 return
   │                       ("처음 맞힌 문제"에만 보상. 재제출 파밍 차단)
   ▼
get_user_progress()    ─── 그 사용자의 progress 행 전부를 2차원 벡터로 로드
   │
   ▼
insert_accept()        ─── accept 에 (problem_id, user_id, solution_id) 기록
update_ac_rate()       ─── pinfo.ac_person_count += 1
   │                       pinfo.first_ac_try += (첫 정답까지 시도 횟수)
   ▼
quest_class 별 분기     ─── 미완료 퀘스트만 골라 각 핸들러로 전달
   │
   ├─ 1 → daliy_quest_process()   (일일)
   ├─ 2 → weekly_quest_process()  (주간)
   ├─ 3 → main_quest_process()    (메인)
   └─ 4 → hidden_quest_process()  (히든)
```

분기 전 필터가 이 구조의 핵심입니다.

```cpp
for (auto& temp : vec)
    if (temp[4] == to_string(i)          // 해당 분류이고
     && temp[2] != temp[3])              // 아직 목표치에 도달하지 않은 것만
        cur.emplace_back(temp);
```

**이미 완료된 퀘스트는 아예 핸들러에 넘어가지 않습니다.** 초과 진행·중복 카운트가 구조적으로 불가능합니다.

### 퀘스트별 핸들러

각 분류 헤더는 `quest_id` 로 스위칭합니다.

| 파일 | 담당 |
|---|---|
| `ac_api.h` | 진입점 · 첫 정답 검증 · 통계 갱신 · 분류 분기 |
| `daily_quest.h` | 출석 체크 · Random-Tag 방어 · 난이도별 랜덤 문제 |
| `weekly_quest.h` | 주간 누적 퀘스트 |
| `main_quest.h` | 메인 퀘스트 (일반 카운트 증가형) |
| `hidden_quest.h` | 히든 퀘스트 (자리 확보) |
| `common_header.h` | 공통 include · MySQL 커넥터 |

**Random-Tag 방어** — 방금 푼 문제의 태그 목록(`problem.source`, 공백 구분)을 파싱해
배정된 태그(`progress.sub_content`)와 일치하는지 확인한 뒤에만 진행도를 올립니다.

```cpp
for (int i{}, j{}, l(strlen(row[0])); i < l; i++)
    if (row[0][i] == ' ' || i == l - 1) {
        string sub = ...;              // 태그 하나 잘라내기
        flag += sub == vec[8];         // sub_content 와 비교
    }
if (!flag) return;                     // 다른 태그 문제 → 진행도 변동 없음
```

**출석 체크 연동** — 출석 체크(`quest_id = 48`)가 오르면
같은 함수 안에서 7일 연속 출석(`quest_id = 54`) 진행도도 함께 올립니다.
두 퀘스트가 항상 같이 움직이도록 한 자리에 묶어 정합성을 보장했습니다.

**진행률 동시 저장** — 모든 핸들러가 `user_prog` 와 `quest_sort_weight` 를 한 UPDATE로 씁니다.

```cpp
int new_user_prog        = min(stoi(vec[2]) + 1, stoi(vec[3]));   // 목표치 초과 방지
int new_quest_sort_weight = (double)new_user_prog / stoi(vec[3]) * 100;
```

정렬용 진행률을 미리 계산해 두었기 때문에 목록 조회 시 `ORDER BY quest_sort_weight DESC` 만으로
**거의 다 깬 퀘스트가 위로** 올라옵니다.

> **참고** — 이 저장소에는 훅 헤더만 들어 있습니다.
> `ac_api_process()` 를 호출하도록 수정한 `judge_client` 본체는 배포 서버에만 있었고,
> 백업에 남은 judge 트리는 HUSTOJ 원본이라 이 저장소에서는 제외했습니다.
> (원본은 [zhblue/hustoj](https://github.com/zhblue/hustoj) 참고)

---

## ② 스케줄러 — `src/judge/scheduler/`

cron으로 도는 독립 C++ 바이너리 두 개입니다.

### `daily_scheduler.cpp` — 매일 00시

```
1. 일일 퀘스트 전체 초기화
   UPDATE progress SET user_prog = 0, quest_rec_rewards = 0, quest_sort_weight = 0
    WHERE quest_class = 1

2. Random-Tag 퀘스트에 태그 새로 배정
   UPDATE progress SET sub_content = (SELECT tag_name FROM tag ORDER BY RAND() LIMIT 1)
    WHERE quest_id = 23

3. 전체 사용자 순회 → 난이도대별로 "아직 안 푼 문제" 중 하나를 지목
   (Beginner / Normal / Advanced → quest_id 64 / 65 / 66)
```

3번이 사용자 수 × 난이도 3개만큼 쿼리를 돌리는 구조라,
**하루 한 번, 트래픽이 없는 시각에만** 도는 별도 프로세스로 뺐습니다.
웹 요청 경로에 두면 첫 접속자가 그 비용을 다 뒤집어씁니다.

문제 선택에는 `random_device` + `mt19937` 을 씁니다.

```cpp
std::random_device rd;
std::mt19937 gen(rd());
std::uniform_int_distribution<int> generator(0, (int)problem_list.size() - 1);
```

후보가 없으면(= 해당 난이도를 다 푼 사용자) `sub_content = 0` 으로 표시해 화면에서 걸러냅니다.

### `weekly_scheduler.cpp` — 매주

`quest_class = 2` 에 대해 같은 초기화를 수행합니다.

---

## ③ 웹 계층

### 요청 흐름

```
GET userinfo.php?user=algokiwi&tab=quest
   │
   ├─ userinfo.php            탭 라우팅 · 권한 체크(본인 또는 운영자)
   │     └ include userinfo/userinfo_quest.php     ← 껍데기
   │
   └─ 로드 후 jQuery 가 두 번 POST
         ├─ quest/profile_db.php   → 좌측 프로필 카드 (LV · EXP 바 · 코인)
         └─ quest/quest_db.php     → 퀘스트 리스트 HTML
```

모든 `*_db.php` 는 동일한 프롤로그로 시작합니다.

```php
$cache_time = 30;
$OJ_CACHE_SHARE = false;         // 사용자별 데이터이므로 공유 캐시 비활성
require_once('../include/cache_start.php');
require_once('../include/db_info.inc.php');
require_once('../include/my_func.inc.php');
require_once('../include/memcache.php');
```

캐시 정책을 파일 단위로 명시합니다.
문제 목록처럼 공유 가능한 것은 캐시를 살리고, 프로필·퀘스트처럼 개인화된 것은 `OJ_CACHE_SHARE = false` 로 끕니다.

### 권한

개인 탭(퀘스트 · 상점 · 인벤토리 · 설정)은 서버에서 막습니다.

```php
if ($user != $_SESSION[$OJ_NAME.'_'.'user_id']) {
    if (!isset($_SESSION[$OJ_NAME.'_'.'administrator'])) {
        echo "<script>location.href='userinfo.php?user=".$user."'</script>";
    }
}
```

### 실시간 채점 현황

`problem/status_change_check.php` 가 현재 필터 조건에 맞는 **최신 `solution_id` 와 결과**만
가볍게 반환하고, 값이 바뀌었을 때만 목록 전체를 다시 요청합니다.
매번 전체를 받지 않으므로 폴링 비용이 낮습니다.

---

## 데이터베이스

### HUSTOJ 원본 (주요)

| 테이블 | 용도 |
|---|---|
| `users` | 계정 (+ AlgoWiki가 `nick_color`, `streak_color`, `comment` 추가) |
| `problem` | 문제 (`difficulty` 로 티어, `source` 에 공백 구분 태그) |
| `solution` | 제출 이력 (결과 · 시간 · 메모리 · 코드 길이) |

### AlgoWiki 추가

| 테이블 | 용도 |
|---|---|
| `uinfo` | `acc_exp` · `coin` · `tier` · `title` · `show_difficulty` · `show_tag` |
| `quests` | 퀘스트 정의 (`quest_class` · `content` · `quest_comp_exp` · `quest_comp_coin` · `quest_image_path`) |
| `progress` | 사용자 × 퀘스트 진행도 (`user_prog` · `quest_sort_weight` · `sub_content` · `quest_rec_rewards`) |
| `accept` | **첫 정답** 기록 — 보상 중복 지급 차단의 근거 |
| `pinfo` | 문제별 통계 (`ac_person_count` · `first_ac_try`) |
| `tag` | 알고리즘 태그 |
| `user_link` | GitHub · Discord · Blog 연결 정보 |

---

## 로컬에서 돌려보려면

이 저장소는 **아카이브**입니다. 그대로 실행되지 않습니다. 최소한 다음이 필요합니다.

1. [HUSTOJ](https://github.com/zhblue/hustoj) 설치 (Apache · PHP · MariaDB · judged)
2. AlgoWiki 추가 테이블 생성 (`uinfo` · `quests` · `progress` · `accept` · `pinfo` · `tag` · `user_link`)
   — 스키마 덤프는 서비스 DB에만 있었으므로 이 저장소에 없습니다
3. `src/web/` 을 HUSTOJ 웹 루트와 `template/<테마>/` 아래로 배치
   — 어느 파일이 어디로 가는지는 [web-structure.md](web-structure.md#1-서버에서의-배치) 참고
4. HUSTOJ의 `include/db_info.inc.php` 에 DB 자격증명 설정
   — 이 저장소에는 HUSTOJ 코어를 포함하지 않았습니다
5. `src/judge/quest_api/` 를 judge_client에 포함하고 AC 경로에서 `ac_api_process()` 호출
6. `src/judge/scheduler/` 빌드 후 cron 등록 (`daily` 매일 00시 / `weekly` 매주)
   — `daily_scheduler.cpp` 의 `password = "CHANGE_ME"` 를 실제 값으로 교체

> **보안** — 커밋 전 DB 비밀번호는 `CHANGE_ME`, 서버 IP는 `<SERVER_IP>` 로 치환했습니다.
> `daily_scheduler.cpp` 처럼 자격증명이 소스에 하드코딩된 부분은
> 실제 재배포 시 환경변수나 설정 파일로 빼는 것이 맞습니다.

---

### 관련 문서

- [gamification.md](gamification.md) — 퀘스트 · 재화 설계 의도
- [file-map.md](file-map.md) — 파일별 역할
