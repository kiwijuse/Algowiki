# 파일별 역할

`src/` 아래 모든 파일이 무엇을 하는지 정리한 문서입니다.

---

## 명명 규칙

파일 이름 접미사가 그 파일의 **계층**을 말해줍니다.

| 패턴 | 역할 | 응답 |
|---|---|---|
| `xxx.php` | **페이지 껍데기** — 레이아웃 · CSS · JS만 들고 있음 | 전체 HTML |
| `xxx_header.php` | 페이지 상단 고정 영역 (필터 · 정렬 · 토글) | HTML 조각 |
| `xxx_db.php` | 데이터 조회 후 **완성된 HTML 조각**을 echo | HTML 조각 |
| `xxx_ajax.php` | 쓰기가 섞인 조각 렌더러 (설정 폼 등) | HTML 조각 / 상태 |

브라우저는 껍데기를 먼저 받고, jQuery가 `_db.php` 들을 POST로 불러 DOM에 꽂습니다.
→ 자세한 배경은 [frontend.md](frontend.md#1-ajax-부분-렌더링--이-프로젝트의-뼈대)

---

## `src/web/` — 웹 계층

### 최상위

| 파일 | 역할 |
|---|---|
| `index.php` | **메인 페이지.** 히어로 · 지그재그 문제 카드 스택 · 게시판 배너 · 소식 카드. 로그인 여부에 따라 퀘스트 링크 분기 |
| `index.css` | 메인 전용 스타일. 절대배치 카드 좌표와 4단계 브레이크포인트 |
| `header.php` | **전역 헤더 / 내비게이션.** OG 메타, 파비콘, 로그인 상태 메뉴, 알림(메일) 뱃지, 시즌 연출(눈 내리기) 로더, `isMobileDevice()` 서버사이드 분기 |
| `menu2.php` | 헤더 드롭다운 메뉴 구성 |
| `problem_list.php` | **문제 목록 페이지 껍데기.** 실제 내용은 `problem/` 아래 렌더러가 채움 |
| `problem.php` | **문제 상세 페이지.** 난이도 티어 표시, 의도된 시간복잡도, 힌트 / 아이템 힌트, 기여자 |
| `status.php` | **채점 현황 페이지 껍데기.** 문제ID · 사용자ID · 언어 · 결과 필터 |
| `status_db.php` | 채점 현황 목록 렌더러 (최상위 버전) |
| `category.php` | **태그 / 위키 목록.** 한글·영문 태그 정렬(한글 우선, 가나다순), 문제 수 정렬, 위키 문서 링크 |
| `userinfo.php` | **프로필 탭 라우터.** `?tab=` 값으로 `userinfo/` 아래 6개 뷰 중 하나를 `require` |
| `quiz.php` | **퀴즈 페이지.** 객관식 / 주관식 개념 퀴즈. 일일 퀘스트와 연동 |
| `reinfo.php` | 채점 결과 상세 (컴파일 에러 · 서브태스크별 결과) |
| `showsource2.php` | **제출 소스 뷰어.** Ace Editor 읽기 전용, 언어별 문법 하이라이트 |
| `board.php` | 게시판 진입 페이지 |
| `board.css` | 게시판 공통 스타일 |
| `help.php` · `faqs.php` | 도움말 · FAQ (HUSTOJ 계승, AlgoWiki에 맞게 수정) |

### 게시판 — 글 단위 액션

| 파일 | 역할 |
|---|---|
| `write.php` | 글쓰기 폼 |
| `post_add.php` | 글 등록 처리 |
| `post_view.php` | 글 상세 보기 |
| `post_edit.php` | 글 수정 폼 |
| `post_edit_commit.php` | 글 수정 반영 |
| `post_delete.php` | 글 삭제 |
| `viewpost.php` | 글 조회 (구 라우트) |
| `comment_add.php` | 댓글 등록 |
| `comment_delete.php` | 댓글 삭제 |

### `board/` — 게시판 분류별 뷰

| 파일 | 역할 |
|---|---|
| `board.php` | 게시판 전체 목록 · 페이지네이션 |
| `free.php` | 자유 게시판 |
| `question.php` | 질문 게시판 |
| `report.php` | 신고 게시판 |
| `search.php` | 제목 + 내용 검색 (공백 제거 후 매칭, 분류별 필터) |

### `problem/` — 문제 · 채점 현황 렌더러

| 파일 | 역할 |
|---|---|
| `problem_header.php` | 문제 목록 상단 — 정렬 기준 드롭다운, 태그 필터, "제목만 표시" / "맞은 문제 표시" 토글 |
| `problem_db.php` | **문제 카드 목록 렌더러.** 태그 칩 · 정답 수 · 제출 수 · 난이도 티어 출력. `uinfo.show_difficulty` 설정(0 안 함 / 1 항상 / 2 푼 것만)에 따라 난이도 노출을 분기 |
| `problem.css` | 문제 목록 카드 · 필터 · 페이지네이션 스타일 (5단계 브레이크포인트 + `pointer: coarse`) |
| `status_db.php` | 채점 현황 목록 렌더러 |
| `status_page_db.php` | 채점 현황 페이지네이션 렌더러 |
| `status_change_check.php` | **실시간 갱신 감시.** 현재 필터 조건의 최신 `solution_id` 와 결과만 반환. 값이 바뀐 경우에만 목록 전체를 다시 요청 |
| `status.css` | 채점 현황 표 스타일 (결과별 색상 뱃지) |
| `userinfo_header.php` | 프로필 "기록" 탭 상단 — 채점 현황 / 맞은 문제 / 만든 문제 전환, 태그 표시 토글 |
| `userinfo_db.php` | 프로필 "기록" 탭 본문 렌더러 (세 가지 뷰 + 페이지네이션) |

### `userinfo/` — 프로필 6개 탭

| 파일 | 역할 |
|---|---|
| `userinfo_profile.php` | **프로필 탭.** 사용자 카드(아바타 · 닉네임 색 · 티어 · LV · 코인), 스트릭 잔디 히트맵, 결과별 통계 도넛, GitHub / Discord / Blog 링크 |
| `userinfo_problem.php` | **기록 탭** 껍데기 (본문은 `problem/userinfo_db.php`) |
| `userinfo_quest.php` | **퀘스트 탭.** 일일 / 주간 / 메인 전환, 육각형 아이콘 CSS, 보상 수령 시 폭죽 연출 |
| `userinfo_shop.php` | **상점 탭** 껍데기 |
| `userinfo_inventory.php` | **인벤토리 탭** 껍데기 |
| `userinfo_setting.php` | **설정 탭** 껍데기 |
| `setting_ajax.php` | 설정 항목별 폼 렌더러 — 비밀번호 · 이메일 · 한줄 소개 · 연결 정보(GitHub / Discord / Blog) · 표시 옵션(난이도 · 태그 · 소스 공개) |
| `php_ajax.php` | 설정 저장 처리 (`uinfo`, `users`, `user_link` UPDATE) |
| `do_sql.php` | 디버그용 SQL 에코 엔드포인트 ⚠️ **개발 중 사용. 운영에 노출되어선 안 되는 파일** |

> **탭 껍데기가 따로 있는 이유** — 6개 탭이 동일한 라디오 버튼 탭 바를 공유하되,
> 자기 탭만 `checked` 상태로 시작해야 하기 때문입니다.
> 개인 탭(퀘스트 · 상점 · 인벤토리 · 설정)은 파일 상단에서 **본인 또는 운영자인지 서버에서 검사**합니다.

### `quest/` — 퀘스트 엔드포인트

| 파일 | 역할 |
|---|---|
| `quest_db.php` | **퀘스트 목록 렌더러.** 분류별 클리어 개수 집계, 진행바, 육각형 아이콘, 히든 퀘스트 `???` 마스킹, 보상 수령 버튼. `quest_sort_weight DESC` 정렬 |
| `profile_db.php` | 좌측 프로필 카드 렌더러. 누적 EXP를 **이분 탐색**해 LV·진행률 계산, 코인 표시 |
| `get_reward.php` | **보상 수령 처리.** 진행도 충족 + 미수령 확인 → `uinfo.acc_exp` / `uinfo.coin` 가산 → `quest_rec_rewards = 1` 마킹 (중복 수령 차단) |

### `include/`

| 파일 | 역할 |
|---|---|
| `db_info.inc.php` | DB 접속 정보 · 사이트 전역 설정 (`$OJ_NAME` 등). **자격증명은 `CHANGE_ME` 로 치환됨** |

---

## `src/judge/` — 채점기 연동 (C++)

### `quest_api/` — 정답 판정 시 진행도 갱신 훅

| 파일 | 역할 |
|---|---|
| `ac_api.h` | **진입점** `ac_api_process()`. 첫 정답 검증(`first_check`) → 진행도 로드 → `accept` 기록 → 문제 통계 갱신 → `quest_class` 별 분기 |
| `daily_quest.h` | 일일 퀘스트 핸들러 — 출석 체크(+7일 스트릭 연동), Random-Tag 방어(태그 일치 검사), 난이도별 랜덤 문제 |
| `weekly_quest.h` | 주간 퀘스트 핸들러 |
| `main_quest.h` | 메인 퀘스트 핸들러 (일반 카운트 증가형) |
| `hidden_quest.h` | 히든 퀘스트 핸들러 |
| `common_header.h` | 공통 include · MySQL 커넥터 |

> `ac_api_process()` 를 호출하도록 수정한 `judge_client` 본체는 배포 서버에만 있었습니다.
> 원본 채점기는 [zhblue/hustoj](https://github.com/zhblue/hustoj) 참고.

### `scheduler/` — cron 배치

| 파일 | 역할 |
|---|---|
| `daily_scheduler.cpp` | 매일 00시 — 일일 퀘스트 초기화, Random-Tag 태그 재배정, 사용자별 난이도대 랜덤 문제 지목(`mt19937`, **안 푼 문제만**) |
| `weekly_scheduler.cpp` | 매주 — 주간 퀘스트 초기화 |

---

## `templates/` — 실제 서비스에 쓰인 HTML 조각 원본

| 파일 | 내용 |
|---|---|
| `nickname-color-rates-1~4.html` | 닉네임 컬러 변경권 등급별 **확률표** (7색 균등 → 24색 4등급) |
| `streak-color-rates.html` | 스트릭 컬러 변경권 확률표 (그라데이션 20종) |
| `lucky-box-rates.html` | 럭키 박스 코인 확률표 |
| `quiz-multiple-choice.html` | 객관식 퀴즈 마크업 틀 |
| `quiz-short-answer.html` | 주관식 퀴즈 마크업 틀 |

---

## `assets/` — 이미지

| 디렉터리 | 내용 |
|---|---|
| `screenshots/` | 실제 서비스 화면 캡처 15장 |
| `brand/` | 로고 · 파비콘 · 아이콘 (시즌 변형 포함) |
| `main/` | 메인 페이지 히어로 · 소식 카드 이미지 |
| `tier/` | 난이도 티어 보석 아이콘 — `v1` / `v2` / `v3` 세 차례 리디자인 |
| `quest/` | 퀘스트 아이콘 |
| `border/` | 프로필 테두리 36종 (PNG 16 · 애니메이션 GIF 20) |
| `inventory/` | 아이템 아이콘 |
| `misc/` | 문제 설명용 애니메이션 GIF |

---

## `archive/` — 참고용 보관

| 디렉터리 | 내용 |
|---|---|
| `drafts/` | **초기 초안.** 지금은 쓰이지 않는 페이지(`wiki.php`, `notice.php`, `contest.php`, `sample.php`, `구현.php`, `수학.php`)와 이전 리비전 |
| `loose-copies/` | 백업에 낱개로 남아 있던 사본. `userinfo.php.hustoj-original`(교체 전 HUSTOJ 원본), `status.php.2024-02`(이전 리비전), 루트에 있던 `problem/` 사본 |

> `archive/drafts/` 안에 `userinfo.php` · `quiz.php` · `status.php` 의 **최종 리비전**이 섞여 있었습니다.
> 이 세 개는 `src/web/` 으로 승격했고, 교체된 이전 버전은 `archive/loose-copies/` 에 남겼습니다.

---

## 이 저장소에 **없는** 것

정직하게 밝혀 둡니다.

| 없는 것 | 이유 |
|---|---|
| 문제 본문 · 테스트 데이터 | 서비스 DB에 있었음 |
| DB 스키마 덤프 | 위와 동일 |
| 위키 문서 본문 | 위와 동일 (렌더링된 모습은 `assets/screenshots/wiki_disjoint_set.png`) |
| `include/` 의 나머지 (`my_func.inc.php`, `memcache.php`, `bbcode.php` 등) | HUSTOJ 원본 — [upstream](https://github.com/zhblue/hustoj) 참고 |
| 수정된 `judge_client` 본체 | 배포 서버에만 존재 |
| 서버 접속 키 · 자격증명 | 의도적으로 제외 |
