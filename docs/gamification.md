# 게임화 시스템 설계

AlgoWiki가 기존 PS 사이트와 다른 지점 전부가 이 문서에 있습니다.

---

## 설계 원칙

1. **보상은 문제를 푸는 행동에서만 나온다.** 재화를 사는 경로는 없다.
2. **재화를 쓸 곳이 항상 있어야 한다.** 쌓이기만 하면 의미가 죽는다.
3. **얻은 것은 남에게 보여야 한다.** 프로필 테두리·닉네임 색은 전역에 노출된다.
4. **확률은 전부 공개한다.** 확률형 아이템의 모든 확률표를 상품 설명에 명시.
5. **막힌 사람을 놓아주지 않는다.** 힌트·시간복잡도 확인권은 이탈 방지 장치다.

---

## 1. 퀘스트

`quests` 테이블의 `quest_class` 로 네 종류를 구분합니다.

| `quest_class` | 종류 | 초기화 | 성격 |
|:---:|---|---|---|
| `1` | **일일 퀘스트** | 매일 (cron) | 매일 올 이유를 만드는 짧은 목표 |
| `2` | **주간 퀘스트** | 매주 (cron) | 누적형 · 보상이 큼 |
| `3` | **메인 퀘스트** | 없음 (영구) | 계정 성장의 큰 줄기 |
| `4` | **히든 퀘스트** | 없음 (영구) | 달성 전까지 제목·내용이 `???` |

### 진행도 모델

사용자별 진행 상태는 `progress` 테이블 한 곳에 모입니다.

| 컬럼 | 의미 |
|---|---|
| `user_id` · `quest_id` | 누가 · 어떤 퀘스트 |
| `user_prog` / `quest_end_prog` | 현재 진행도 / 목표치 |
| `quest_rec_rewards` | 보상 수령 여부 (0/1) |
| `quest_sort_weight` | **진행률(%)** — 목록 정렬 기준 |
| `sub_content` | 퀘스트별 동적 파라미터 (배정된 태그명, 지목된 문제 번호 등) |

`quest_sort_weight` 가 이 설계의 작은 핵심입니다.
진행률을 매번 계산해 저장해두고 `ORDER BY quest_sort_weight DESC` 로 정렬하므로,
**거의 다 깬 퀘스트가 항상 위로 올라옵니다.** "조금만 더 하면 되는데" 를 눈에 띄게 만드는 장치입니다.

### 대표 퀘스트

| 퀘스트 | 조건 | EXP | 코인 |
|---|---|---:|---:|
| **Random - Tag** | 매일 무작위 배정된 **태그**의 문제 1개 해결 | 150 | 15 |
| **출석 체크** | 아무 문제나 1개 해결 | 50 | 5 |
| **Random - Beginner** | 지목된 Beginner 난이도 문제 해결 | 50 | 5 |
| **Random - Normal** | 지목된 Normal 난이도 문제 해결 | 75 | 7 |
| **Random - Advanced** | 지목된 Advanced 난이도 문제 해결 | 100 | 10 |
| **산뜻한 출발** | 퀴즈 1개 풀기 | 20 | 2 |
| **알찬 한 주** | 주간 누적 풀이 | — | — |
| **꾸준함** | 7일 연속 출석 (출석 체크와 연동 상승) | — | — |

### 랜덤 퀘스트가 만들어지는 방식

일일 스케줄러가 매일 자정에 사용자마다 문제를 **새로 지목**합니다.
이때 **이미 푼 문제는 후보에서 제외**합니다.

```sql
SELECT problem_id FROM problem
 WHERE difficulty IN (:diff, :diff + 1)      -- 티어의 Ⅰ·Ⅱ 두 단계
   AND defunct = 'N'
   AND problem_id NOT IN (
        SELECT problem_id FROM accept WHERE user_id = :user   -- 안 푼 것만
   );
```

후보 중 하나를 `mt19937` 로 뽑아 `progress.sub_content` 에 저장합니다.
후보가 하나도 없으면 `sub_content = 0` 으로 두어 화면에서 걸러냅니다.

태그 퀘스트도 같은 방식으로 매일 태그를 갈아끼웁니다.

```sql
UPDATE progress
   SET sub_content = (SELECT tag_name FROM tag ORDER BY RAND() LIMIT 1)
 WHERE quest_id = 23;
```

### 보상 수령

진행도가 다 찼다고 자동으로 지급하지 않습니다. **직접 눌러야** 합니다.

```php
if ($user_prog >= $quest_end_prog && $quest_rec_rewards == 0) {
    // quests 에서 보상량을 읽어 uinfo.acc_exp / uinfo.coin 에 가산
    // quest_rec_rewards = 1 로 마킹 (중복 수령 차단)
}
```

누르는 순간 폭죽이 터지고 코인·EXP 카드가 갱신됩니다.
"받는 행동"을 남겨둔 건 의도적입니다 — 성취를 확인하는 순간이 있어야 하기 때문입니다.

---

## 2. 성장 — 레벨과 티어

### 레벨

`uinfo.acc_exp` (누적 경험치) 하나만 저장하고, 레벨은 **조회 시점에 이분 탐색**으로 계산합니다.

```php
$l = 1; $r = 30000; $idx = 0;
while ($l <= $r) {
    $mid = intval(($l + $r) / 2);
    if ($exp_total[$mid] > $exp_point) $r = $mid - 1;
    else { $idx = $mid; $l = $mid + 1; }
}
$lv          = $idx + 1;
$need_exp    = $exp_point - $exp_total[$idx];
$exp_percent = $need_exp / $exp_a[$idx + 1] * 100;
```

레벨 테이블이 바뀌어도 사용자 데이터를 마이그레이션할 필요가 없다는 게 이 방식의 장점입니다.

### 난이도 티어

<table>
<tr>
<td align="center"><img src="../assets/tier/v3/None.png" width="46"><br><sub>None</sub></td>
<td align="center"><img src="../assets/tier/v3/Beginner1.png" width="46"><br><sub>Beginner</sub></td>
<td align="center"><img src="../assets/tier/v3/Easy1.png" width="46"><br><sub>Easy</sub></td>
<td align="center"><img src="../assets/tier/v3/Normal1.png" width="46"><br><sub>Normal</sub></td>
<td align="center"><img src="../assets/tier/v3/Advanced1.png" width="46"><br><sub>Advanced</sub></td>
<td align="center"><img src="../assets/tier/v3/Hard1.png" width="46"><br><sub>Hard</sub></td>
<td align="center"><img src="../assets/tier/v3/Challenge1.png" width="46"><br><sub>Challenge</sub></td>
</tr>
</table>

7개 티어 × Ⅰ·Ⅱ 두 단계 = **13단계**. `problem.difficulty` 정수값을 색·이름·아이콘으로 매핑합니다.

난이도 노출은 사용자가 고를 수 있습니다 — `uinfo.show_difficulty`

| 값 | 동작 | 왜 필요한가 |
|:---:|---|---|
| `0` | 표시 안 함 | 선입견 없이 풀고 싶은 사람 |
| `1` | 항상 표시 | 난이도 보고 고르고 싶은 사람 |
| `2` | **내가 푼 문제만** 표시 | 풀기 전엔 모르고, 푼 뒤에 확인하고 싶은 사람 |

티어 아이콘은 v1 → v2 → v3 세 번 다시 그렸습니다 (`assets/tier/`).

---

## 3. 재화와 상점

**알고 코인** 하나만 씁니다. 현금 결제 경로는 없습니다.

<img src="../assets/screenshots/userinfo_shop.png" width="380" align="right">

### 상점 구획

| 구획 | 갱신 | 내용 |
|---|---|---|
| **특가 상품** | 30시간 타이머 | 프로필 테두리 34~41% 할인 (2,000 → 1,180~1,320) |
| **데일리 상품** | 6시간 타이머 | 난이도별 힌트권 (Easy 15 / Advanced 50) |
| **상시 상품** | 고정 | 리롤권 · 힌트권 · 복권 · 확인권 |
| **프로필 테두리** | 고정 | 36종 · 각 2,000 코인 |

남은 시간을 카운트다운으로 보여주고, 할인율 배지를 카드 모서리에 얹었습니다.
"지금 아니면 사라진다"는 압박을 만들되, **재화를 살 수 없으니** 결국 문제를 풀게 됩니다.

<br clear="right">

### 아이템 목록

| 아이템 | 가격 | 효과 |
|---|---:|---|
| **난이도별 힌트권** | 15~50 | 해당 난이도 이하 문제의 상세 힌트 해금 |
| **시간복잡도 확인권** | 25 | 문제의 *의도된* 시간복잡도 공개 |
| **Random - Beginner 리롤권** | 10 | 지목된 문제 다시 뽑기 |
| **Random - Normal 리롤권** | 15 | 〃 |
| **Random - Advanced 리롤권** | 20 | 〃 |
| **Random - Tag 퀘스트 리롤** | 30 | 배정된 태그 다시 뽑기 |
| **일일퀘스트 리롤권** | 50 | 일일 퀘스트 세트 전체 재구성 |
| **닉네임 컬러 변경권 1~4** | 10~ | 등급별 색상 풀에서 무작위 획득 |
| **스트릭 컬러 변경권** | 50 | 잔디 그라데이션 색 무작위 획득 |
| **알고 복권** | 50 | 확률형 |
| **럭키 박스** | 100 | 코인 확률 지급 |
| **프로필 테두리** | 2,000 | 36종 중 지정 구매 |

### 확률 공개

실제 서비스에서 상품 설명에 붙였던 확률표 HTML 원본이
[`templates/`](../templates/) 에 그대로 있습니다.

**닉네임 컬러 변경권** — 등급이 오를수록 색 풀이 넓어지고 상위 색이 추가됩니다.

| 등급 | 색 수 | 확률 구성 |
|:---:|---:|---|
| 1 | 7 | 전부 14.28% (균등) |
| 2 | 13 | 기본 7색 11.42% + 상위 6색 3.33% |
| 3 | 18 | 기본 14색 6.15% + 최상위 5색 4.00% |
| 4 | 24 | 기본 18색 4.44% + 최상위 6색 3.33% |

**럭키 박스**

| 결과 | 확률 |
|---|---:|
| 0 코인 | 50% |
| 100 코인 | 32% |
| 200 코인 | 10% |
| 300 코인 | 5% |
| 500 코인 | 3% |

기대값 78코인 < 가격 100코인. **손해 보는 상품이지만 확률을 다 공개**했습니다.
"뽑는 재미"는 남기되 속이지는 않는다는 기준이었습니다.

---

## 4. 꾸미기 — 얻은 것을 보여주기

| 요소 | 저장 위치 | 노출 범위 |
|---|---|---|
| **프로필 테두리** | 사용자 인벤토리 → 착용 | 프로필 카드 |
| **닉네임 색** | `users.nick_color` | **사이트 전역** (프로필 · 채점 현황 · 랭킹 · 게시판) |
| **스트릭 색** | `users.streak_color` | 잔디 히트맵 |
| **칭호 / 티어** | `uinfo.title`, `uinfo.tier` | 프로필 카드 |
| **한줄 소개** | `users.comment` | 프로필 카드 |
| **GitHub / Discord / Blog** | `user_link` | 프로필 카드 (개별 공개 토글) |

프로필 테두리 36종 — 정지 이미지 16종 + **애니메이션 GIF 20종**.
GIF 쪽이 상위 등급이고, 특가 상품에 올라오는 것도 대체로 GIF입니다.

<table>
<tr>
<td><img src="../assets/border/profile_border1.png" width="76"></td>
<td><img src="../assets/border/profile_border5.png" width="76"></td>
<td><img src="../assets/border/profile_border12.png" width="76"></td>
<td><img src="../assets/border/profile_border20.gif" width="76"></td>
<td><img src="../assets/border/profile_border28.gif" width="76"></td>
<td><img src="../assets/border/profile_border34.gif" width="76"></td>
</tr>
</table>

닉네임 색을 **전역에 반영한 것**이 중요한 결정이었습니다.
프로필 안에서만 보이면 아무도 안 삽니다. 채점 현황과 게시판에서 남의 눈에 띄어야 재화를 쓸 이유가 생깁니다.

---

## 5. 힌트 — 이탈 방지 장치

게임화의 목적은 재미만이 아니라 **완주율**입니다.
막힌 사람이 그냥 창을 닫는 걸 막아야 합니다.

문제 페이지는 힌트를 두 겹으로 나눕니다.

1. **기본 힌트** — 누구나 볼 수 있는 방향 제시
2. **아이템 힌트** — 힌트권으로 해금. 언어별 코드 골격까지 제공

<img src="../assets/screenshots/problem.png" width="620">

시간복잡도 확인권은 별도입니다.
"이 문제는 O(N log N)으로 풀어야 한다"만 알아도 접근이 확 달라지는 경우가 많아,
**답을 주지 않으면서 방향만 주는** 아이템으로 따로 분리했습니다.

---

### 관련 문서

- [architecture.md](architecture.md) — 진행도가 실제로 갱신되는 지점
- [frontend.md](frontend.md) — 이 시스템들의 화면 구현
