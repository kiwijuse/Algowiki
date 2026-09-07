# 웹 구조

AlgoWiki가 **웹 서버 위에서 어떻게 배치되고 요청이 어떤 경로로 흐르는지** 정리했습니다.

---

## 1. 서버에서의 배치

이 저장소의 `src/web/` 은 편의상 한 폴더에 모았을 뿐
실제 서버에서는 **두 위치로 나뉘어** 있었습니다.

```
/home/judge/src/web/                     ← 웹 루트 (DocumentRoot)
│
├─ index.php            ┐
├─ problem_list.php     │
├─ problem.php          │  최상위 페이지
├─ status.php           │  URL 이 곧 파일명
├─ category.php         │
├─ quiz.php             │
├─ board.php            │
├─ userinfo.php         ┘
│
├─ problem/             ┐
├─ userinfo/            │  페이지가 내부적으로 require / POST 하는
├─ quest/               │  뷰 · 렌더러 디렉터리 (직접 URL로 열지 않음)
├─ board/               ┘
│
├─ include/             ← HUSTOJ 코어 (db_info · my_func · memcache · bbcode …)
│                          ※ 이 저장소에 포함하지 않음
│
└─ template/
   └─ algowiki/         ← 테마 디렉터리
      ├─ header.php     ← 전역 헤더 · 내비게이션
      ├─ footer.php
      ├─ index.css
      └─ js/
```

**핵심 규칙** — 최상위 페이지는 전부 이 형태로 시작하고 끝납니다.

```php
require_once('./include/db_info.inc.php');       // 설정 · DB
require_once('./include/my_func.inc.php');       // 공통 함수
include("template/$OJ_TEMPLATE/header.php");     // 헤더
   …                                             // 페이지 본문
include("template/$OJ_TEMPLATE/footer.php");     // 푸터
```

`$OJ_TEMPLATE` 로 테마를 갈아끼울 수 있는 HUSTOJ 구조를 그대로 따랐습니다.
덕분에 **AlgoWiki의 전 화면을 테마 하나로 격리**할 수 있었고 코어를 건드리지 않아도 됐습니다.

---

## 2. URL 맵

헤더 내비게이션이 그대로 사이트맵입니다.

| 메뉴 | URL | 파일 |
|---|---|---|
| **문제** | `/problem_list.php` | `problem_list.php` |
| ↳ 문제 상세 | `/problem.php?id=1000` | `problem.php` |
| ↳ 태그 필터 | `/problem_list.php?filter1=구현` | 〃 |
| **퀴즈** | `/quiz.php` | `quiz.php` |
| **태그/위키** | `/category.php` | `category.php` |
| **채점 현황** | `/status.php` | `status.php` |
| ↳ 조건 검색 | `/status.php?problem_id=1000&user_id=…` | 〃 |
| **게시판** | `/board.php` | `board.php` |
| ↳ 분류별 | `/board.php?data=free` \| `question` \| `report` | `board/*.php` |
| ↳ 검색 | `/board.php?data=search&cate=…&search=…` | `board/search.php` |
| **프로필** | `/userinfo.php?user=algokiwi` | `userinfo.php` |
| ↳ 탭 | `/userinfo.php?user=…&tab=quest` | `userinfo/*.php` |
| 랭킹 · 대회 · 로그인 등 | `/ranklist.php`, `/contest.php`, `/loginpage.php` | *HUSTOJ 코어* |

---

## 3. 세 가지 라우팅 패턴

### 패턴 A — 파일이 곧 URL

가장 단순한 형태. `index.php`, `problem.php`, `quiz.php`, `category.php` 가 여기 해당합니다.

```
GET /category.php
   └─ category.php  →  태그 목록 조회 · 정렬 · 표 생성 · 출력
```

### 패턴 B — 쿼리 파라미터로 하위 뷰 분기

한 URL을 유지한 채 내용만 갈아끼웁니다. **탭 UI와 게시판**이 이 방식입니다.

```php
// userinfo.php — 프로필 6개 탭
$tab = isset($_GET['tab']) ? $_GET['tab'] : 'profile';

if      ($tab == 'profile')   require("userinfo/userinfo_profile.php");
else if ($tab == 'problem')   require("userinfo/userinfo_problem.php");
else if ($tab == 'quest')     require("userinfo/userinfo_quest.php");
else if ($tab == 'shop')      require("userinfo/userinfo_shop.php");
else if ($tab == 'inventory') require("userinfo/userinfo_inventory.php");
else if ($tab == 'setting')   require("userinfo/userinfo_setting.php");
else                          require("userinfo/userinfo_profile.php");
```

```php
// board.php — 게시판 분류
if (isset($_GET['data'])) require("./board/".$_GET['data'].".php");
else                      require("./board/board.php");
```

> ⚠️ `board.php` 의 분기는 `$_GET['data']` 를 파일 경로에 그대로 넣습니다.
> 지금 다시 짠다면 **허용 목록(whitelist)으로 검증**해야 할 부분입니다.
> (`free` / `question` / `report` / `search` 외에는 거부)

### 패턴 C — 껍데기 + AJAX 조각 (이 프로젝트의 주력)

목록·필터·페이지네이션이 있는 화면은 전부 이 방식입니다.

```
GET /problem_list.php
   │
   ├─ problem_list.php              페이지 껍데기 (높이 확보 · CSS · JS)
   │     └ require problem/problem_header.php    정렬 · 필터 · 토글 UI
   │
   └─ 로드 후 jQuery
         POST problem/problem_db.php  { page, filter1..3, show_tag, sort }
              └─▶ 문제 카드 HTML 조각을 그대로 응답
                  → $('#list').html(data)
```

사용자가 필터를 바꾸면 **페이지 이동 없이** `problem_db.php` 만 다시 부릅니다.

같은 패턴이 적용된 곳:

| 화면 | 껍데기 | 조각 렌더러 |
|---|---|---|
| 문제 목록 | `problem_list.php` | `problem/problem_header.php` → `problem/problem_db.php` |
| 채점 현황 | `status.php` | `problem/status_db.php` · `status_page_db.php` · `status_change_check.php` |
| 프로필 · 기록 | `userinfo/userinfo_problem.php` | `problem/userinfo_header.php` → `problem/userinfo_db.php` |
| 퀘스트 | `userinfo/userinfo_quest.php` | `quest/profile_db.php` · `quest/quest_db.php` |
| 설정 | `userinfo/userinfo_setting.php` | `userinfo/setting_ajax.php` → `userinfo/php_ajax.php` |

---

## 4. 요청 생명주기 — 퀘스트 탭 예시

가장 복잡한 화면을 끝까지 따라가 보겠습니다.

```
① GET /userinfo.php?user=algokiwi&tab=quest
   │
   ├─ userinfo.php
   │    ├ include/ 코어 로드 (세션 · DB · 캐시)
   │    ├ 사용자 존재 확인 → 없으면 error/user_notfound.php
   │    └ tab=quest  →  require userinfo/userinfo_quest.php
   │
   ├─ userinfo/userinfo_quest.php
   │    ├ 권한 검사: 본인 또는 운영자가 아니면 프로필로 되돌림
   │    ├ template/algowiki/header.php  (전역 헤더)
   │    ├ 탭 바 · 육각형 아이콘 CSS · 폭죽 스크립트
   │    └ 빈 컨테이너 두 개만 남기고 응답 종료
   │
   ▼ 브라우저 렌더링 완료
②    jQuery 가 두 개를 병렬 POST
   │
   ├─ POST quest/profile_db.php   { user }
   │     └ 프로필 카드 HTML 응답 (LV · EXP 바 · 코인)
   │
   └─ POST quest/quest_db.php     { user, quest: '일일' }
         └ 진행바 + 퀘스트 카드 목록 HTML 응답
           (진행률 높은 순 정렬)

③ [보상 수령] 클릭
   │
   ├─ POST quest/get_reward.php   { user, quest_id }
   │     └ 수령 가능한지 확인하고 EXP · 코인 지급
   │
   └─ 10ms 뒤: 폭죽 → profile_db.php 재요청 → quest_db.php 재요청
                (코인·EXP·퀘스트 상태가 동시에 갱신됨)
```

**포인트** — ③에서 페이지 전체를 새로고침하지 않습니다.
보상을 받은 순간의 연출(폭죽)이 끊기지 않으면서 화면 두 곳만 정확히 갱신됩니다.

---

## 5. 계층 정리

```
┌─────────────────────────────────────────────────────────┐
│  테마 계층      template/algowiki/header.php · footer.php│
│                 모든 페이지가 공유하는 헤더 · 내비 · 푸터  │
├─────────────────────────────────────────────────────────┤
│  페이지 계층    index / problem_list / problem / status  │
│                 category / quiz / board / userinfo      │
│                 → 라우팅 · 권한 · 레이아웃 · CSS · JS     │
├─────────────────────────────────────────────────────────┤
│  뷰 계층        userinfo/*.php   board/*.php             │
│                 → 탭·분류별 화면 골격                     │
├─────────────────────────────────────────────────────────┤
│  렌더러 계층    *_header.php  (상단 컨트롤)               │
│                 *_db.php      (데이터 → HTML 조각)       │
│                 *_ajax.php    (읽기 + 쓰기)              │
├─────────────────────────────────────────────────────────┤
│  코어 계층      include/  ※ HUSTOJ — 이 저장소에 없음    │
│                 세션 · DB 접근 · 캐시 · 다국어            │
└─────────────────────────────────────────────────────────┘
```

계층이 위에서 아래로만 의존합니다.
렌더러는 자기가 어느 페이지에서 불렸는지 모르고 `POST` 파라미터만 보고 HTML을 만듭니다.
그래서 **같은 렌더러를 여러 페이지에서 재사용**할 수 있었습니다
(`problem/userinfo_db.php` 는 프로필 "기록" 탭과 채점 현황 양쪽에서 쓰입니다).

---

## 6. 캐시 정책

AlgoWiki는 화면 대부분이 개인화되어 있습니다 —
난이도 표시 방식, 맞은 문제 표시, 프로필·퀘스트·상점은 사람마다 내용이 다릅니다.
그래서 여러 사용자가 공유하는 캐시는 **사실상 쓰지 않습니다.**

대신 **AJAX로 조각만 다시 받기** 때문에 전체 페이지를 다시 만드는 비용이 애초에 발생하지 않습니다.
필터를 바꿔도 갱신되는 건 목록 영역 하나뿐입니다.

---

## 7. 이 저장소에 없는 웹 파일

`include/` 코어와 로그인·랭킹·대회 등 **HUSTOJ가 제공하는 페이지**는 포함하지 않았습니다.
원본은 [zhblue/hustoj](https://github.com/zhblue/hustoj) 를 참고하세요.

| 없는 것 | 무엇 |
|---|---|
| `include/*` | `db_info.inc.php` · `my_func.inc.php` · `memcache.php` · `bbcode.php` · `cache_start.php` · `setlang.php` |
| 인증 | `loginpage.php` · `logout.php` · `registerpage.php` |
| 랭킹 · 대회 | `ranklist.php` · `contest.php` · `contestrank.php` |
| 관리자 | `admin/` 전체 |
| 에러 · 안내 | `reinfo.php` · `faqs.php` · `showsource2.php` · `error/*` |

---

### 관련 문서

- [file-map.md](file-map.md) — 파일별 역할
- [frontend.md](frontend.md) — 이 구조를 고른 이유
- [architecture.md](architecture.md) — 웹 바깥(채점기 · 스케줄러)
