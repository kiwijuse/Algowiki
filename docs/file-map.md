# 파일별 역할

`backup/수정본` — 실제 서비스에 올라가 있던 파일 전부에 대한 설명입니다.
**저장소에 포함한 것과 제외한 것을 모두** 적었습니다.

> 웹 서버에서 이 파일들이 어떻게 배치되고 요청이 어떤 경로로 흐르는지는
> [web-structure.md](web-structure.md) 를 먼저 보시면 이해가 빠릅니다.

---

## 명명 규칙

파일 이름 접미사가 그 파일의 **계층**을 말해줍니다.

| 패턴 | 역할 | 응답 |
|---|---|---|
| `xxx.php` | **페이지 껍데기** — 라우팅 · 레이아웃 · CSS · JS | 전체 HTML |
| `xxx_header.php` | 페이지 상단 고정 영역 (필터 · 정렬 · 토글) | HTML 조각 |
| `xxx_db.php` | 데이터 조회 후 **완성된 HTML 조각**을 echo | HTML 조각 |
| `xxx_ajax.php` | 읽기 + 쓰기가 섞인 조각 렌더러 (설정 폼 등) | HTML 조각 / 상태 |

브라우저는 껍데기를 먼저 받고, jQuery가 `_db.php` 들을 POST로 불러 DOM에 꽂습니다.

---

# 포함한 파일

## `src/web/` — 최상위 페이지

| 파일 | 역할 |
|---|---|
| `index.php` | **메인 페이지.** 히어로("게임처럼 배우는 알고리즘") · 지그재그 문제 카드 스택 · 게시판 배너 · 소식 카드 4장. 로그인 여부로 퀘스트 링크 분기, 모바일이면 문구 축약 |
| `index.css` | 메인 전용 스타일. `box1~box5` 절대배치 좌표와 4단계 브레이크포인트 (`991 / 850+coarse / 670 / 550`) |
| `header.php` | **전역 헤더 / 내비게이션.** OG 메타 · 파비콘 · 메뉴(문제·퀴즈·태그/위키·채점현황·랭킹·게시판·기타) · 로그인 상태 메뉴 · 새 메일 뱃지 · 시즌 눈내리기 로더 · `isMobileDevice()` 서버사이드 분기 · 글래스모피즘 `.padding` 정의 |
| `problem_list.php` | **문제 목록 껍데기.** 높이를 미리 확보해 AJAX 로드 중 레이아웃이 튀지 않게 함. 본문은 `problem/problem_header.php` |
| `problem.php` | **문제 상세.** 난이도 티어(색 + 보석 아이콘) · 의도된 시간복잡도 · 시간/메모리 제한 · 제출/정답/정답률 · 힌트 · 아이템 힌트 · 기여자. `show_difficulty` 설정에 따라 난이도 노출 분기 |
| `status.php` | **채점 현황 껍데기.** 문제ID · 사용자ID · 언어 · 결과 4개 필터의 상태를 URL 쿼리로 관리 |
| `status_db.php` | 채점 현황 목록 렌더러 (최상위에서 쓰이는 버전) |
| `category.php` | **태그 / 위키 목록.** 한글 태그를 앞, 영문 태그를 뒤로 두고 각각 가나다·알파벳 정렬. 문제 수 기준 정렬도 지원. 위키 문서가 있는 태그에 돋보기 링크. `Mo's` 처럼 따옴표가 든 태그명을 URL-safe 하게 치환 |
| `userinfo.php` | **프로필 탭 라우터.** `?tab=` 값으로 `userinfo/` 아래 6개 뷰 중 하나를 `require`. 존재하지 않는 사용자면 에러 페이지로 |
| `quiz.php` | **퀴즈 페이지.** 객관식 · 주관식 개념 퀴즈. 정답 확인 후 해설 노출, 일일 퀘스트와 연동 |
| `board.php` | **게시판 라우터 + 검색 UI.** `?data=` 값으로 `board/` 아래 뷰를 `require`. 분류 드롭다운과 검색창 스크립트 포함 |
| `board.css` | 게시판 공통 스타일 |

## `src/web/` — 게시판 글 단위 액션

| 파일 | 역할 |
|---|---|
| `write.php` | 글쓰기 폼 (분류 선택 · 제목 · 본문) |
| `post_add.php` | 글 등록 처리 |
| `post_view.php` | 글 상세 — 본문 · 댓글 목록 · 댓글 입력 |
| `post_edit.php` | 글 수정 폼 |
| `post_edit_commit.php` | 글 수정 반영 |
| `post_delete.php` | 글 삭제 |
| `viewpost.php` | 글 조회 (구 라우트, 하위 호환용) |
| `comment_add.php` | 댓글 등록 |
| `comment_delete.php` | 댓글 삭제 |

## `src/web/board/` — 게시판 분류별 뷰

| 파일 | 역할 |
|---|---|
| `board.php` | 전체 글 목록 · 페이지네이션 |
| `free.php` | 자유 게시판 |
| `question.php` | 질문 게시판 |
| `report.php` | 신고 게시판 |
| `search.php` | 제목 + 내용 검색. 검색어의 공백을 제거해 매칭하고 분류별로 좁힘 |

## `src/web/problem/` — 문제 · 채점 현황 렌더러

| 파일 | 역할 |
|---|---|
| `problem_header.php` | 문제 목록 상단 컨트롤 — 정렬(문제 번호순 / 인기순), 태그 필터 드롭다운(최대 3개, 선택 시 칩으로 표시), **제목만 표시** · **맞은 문제 표시** 토글 |
| `problem_db.php` | **문제 카드 목록 렌더러.** 카드형/리스트형 두 뷰, 태그 칩, 정답·제출 수, 난이도 티어. `uinfo.show_difficulty`(0 안 함 / 1 항상 / 2 푼 것만)에 따라 난이도를 `?` 로 가리거나 노출. 맞은 문제는 카드 테두리를 초록으로 |
| `problem.css` | 문제 목록 카드 · 필터 · 페이지네이션 스타일. 5단계 브레이크포인트 + `pointer: coarse` 분기 |
| `status_db.php` | 채점 현황 목록 렌더러. 결과별 색상 뱃지, 사용자 닉네임 색 반영 |
| `status_page_db.php` | 채점 현황 페이지네이션 렌더러 (페이지 세트 단위 `≪ ‹ 1 2 3 › ≫`) |
| `status_change_check.php` | **실시간 갱신 감시.** 현재 필터 조건에 맞는 최신 `solution_id` 와 결과만 가볍게 반환. 값이 바뀐 경우에만 목록 전체를 다시 요청해 폴링 비용을 낮춤 |
| `status.css` | 채점 현황 표 스타일 (정답 초록 / 틀림 빨강 / 시간초과·런타임·컴파일 에러 노랑) |
| `userinfo_header.php` | 프로필 "기록" 탭 상단 — 채점 현황 / 맞은 문제 / 만든 문제 전환 드롭다운, 태그 표시 토글 |
| `userinfo_db.php` | 프로필 "기록" 탭 본문 렌더러. 위 세 가지 뷰를 각각 다른 쿼리로 그리고 페이지네이션까지 담당 |

## `src/web/userinfo/` — 프로필 6개 탭

| 파일 | 역할 |
|---|---|
| `userinfo_profile.php` | **프로필 탭.** 사용자 카드(아바타 · 프로필 테두리 · 닉네임 색 · 칭호 · 티어 · LV · EXP 바 · 코인), 스트릭 잔디 히트맵(색상 아이템 반영), 채점 결과별 통계 도넛, GitHub 아바타 연동, Discord / Blog 링크. 최대 파일(27KB) |
| `userinfo_problem.php` | **기록 탭** 껍데기 (본문은 `problem/userinfo_db.php`) |
| `userinfo_quest.php` | **퀘스트 탭.** 일일/주간/메인 전환 버튼, 육각형 아이콘 `clip-path` CSS, 진행바, 보상 수령 시 `canvas-confetti` 폭죽 + 프로필·목록 동시 갱신 |
| `userinfo_shop.php` | **상점 탭** 껍데기 |
| `userinfo_inventory.php` | **인벤토리 탭** 껍데기 |
| `userinfo_setting.php` | **설정 탭** 껍데기. 좌측 사이드바(내 정보 / 설정) 구성 |
| `setting_ajax.php` | 설정 항목별 폼 렌더러 — 비밀번호 · 이메일 · 한줄 소개 · 연결 정보(GitHub·Discord·Blog) · 표시 옵션(난이도·태그) · 공개 옵션 |
| `php_ajax.php` | 설정 저장 처리. `uinfo` · `users` · `user_link` 를 항목별로 UPDATE |
| `do_sql.php` | 개발 중 쓰던 SQL 에코 엔드포인트 ⚠️ **운영에 노출되어선 안 되는 파일.** 당시 실수를 기록으로 남겨둠 |

> **탭 껍데기가 6개나 따로 있는 이유** — 모두 같은 라디오 버튼 탭 바를 공유하되
> 자기 탭만 `checked` 로 시작해야 하기 때문입니다.
> 개인 탭(퀘스트 · 상점 · 인벤토리 · 설정)은 파일 상단에서 **본인 또는 운영자인지 서버에서 검사**합니다.

## `src/web/quest/` — 퀘스트 엔드포인트

| 파일 | 역할 |
|---|---|
| `quest_db.php` | **퀘스트 목록 렌더러.** 분류별 클리어 수 집계 → 진행바, 육각형 아이콘, 히든 퀘스트 `???` 마스킹, 조건 안의 태그명·문제번호를 링크로, 완료 시 보상 수령 버튼. 진행률 높은 순으로 정렬해 거의 다 깬 퀘스트를 위로 |
| `profile_db.php` | 좌측 프로필 카드 렌더러. LV · EXP 바 · 코인 표시, 다음 레벨까지 필요 EXP 툴팁 |
| `get_reward.php` | **보상 수령 처리.** 수령 가능한 상태인지 확인하고 EXP · 코인을 지급, 중복 수령 차단 |

## `src/judge/` — 채점기 연동 (C++)

게임화를 위해 서버 쪽에 얹은 두 조각입니다. 자세한 배경은 [architecture.md](architecture.md).

| 위치 | 역할 |
|---|---|
| `quest_api/` | 채점기가 **정답을 판정하는 순간** 퀘스트 진행도를 올리는 훅.<br>분류별(일일 / 주간 / 메인 / 히든)로 파일이 나뉘어 있습니다 |
| `scheduler/` | 일일 · 주간 퀘스트를 초기화하고 그날의 랜덤 문제·태그를 새로 지목하는 cron 배치 |

## `templates/` — 서비스에 쓰인 HTML 조각 원본

| 파일 | 내용 |
|---|---|
| `nickname-color-rates-1~4.html` | 닉네임 컬러 변경권 등급별 **확률표** (7색 균등 → 24색 4등급) |
| `streak-color-rates.html` | 스트릭 컬러 변경권 확률표 (그라데이션 20종) |
| `lucky-box-rates.html` | 럭키 박스 코인 확률표 |
| `quiz-multiple-choice.html` | 객관식 퀴즈 마크업 틀 |
| `quiz-short-answer.html` | 주관식 퀴즈 마크업 틀 |

## `assets/` — 이미지

| 디렉터리 | 내용 |
|---|---|
| `screenshots/` | 실제 서비스 화면 캡처 15장 |
| `brand/` | 로고 · 파비콘 · 아이콘 (크리스마스 변형 포함) · 외부 링크 마크 |
| `main/` | 메인 히어로 · 소식 카드 이미지 |
| `tier/` | 난이도 티어 보석 — `v1` / `v2` / `v3` 세 차례 리디자인 |
| `quest/` | 퀘스트 아이콘 29종 |
| `border/` | 프로필 테두리 36종 (PNG 16 · 애니메이션 GIF 20) |
| `inventory/` | 아이템 아이콘 |
| `misc/` | 문제 설명용 애니메이션 GIF |

---

# 제외한 파일

## HUSTOJ 원본 · 파생 — 출처만 남김

이 저장소는 **AlgoWiki 팀이 직접 만든 것만** 담습니다.
아래는 HUSTOJ가 제공하거나 그것을 거의 그대로 쓴 파일이라 제외했습니다.
원본은 [zhblue/hustoj](https://github.com/zhblue/hustoj) 에 있습니다.

| 파일 | 무엇 |
|---|---|
| `judge/` 전체 (hustoj.tar.gz 포함, 80MB) | HUSTOJ 채점기 · 웹 · 설치 스크립트 원본 트리 |
| `db_info.inc.php` · `db.info.inc.php 수정본.txt` | HUSTOJ 설정 파일 (DB 자격증명 포함) |
| `help.php` | HUSTOJ 관리자 시스템 상태 페이지 |
| `menu2.php` | HUSTOJ 관리자 사이드 메뉴 |
| `faqs.php` | HUSTOJ FAQ (컴파일 옵션 안내). 한국어로 번역만 한 것 |
| `reinfo.php` | HUSTOJ 채점 에러 안내. 대부분이 `$MSG_*` 상수 매핑 |
| `showsource2.php` | HUSTOJ 소스 뷰어 (Ace 래퍼) |
| `include/` 나머지 | `my_func.inc.php` · `memcache.php` · `bbcode.php` · `cache_start.php` · `setlang.php` |
| 인증 · 랭킹 · 대회 · 관리자 페이지 | `loginpage.php` · `ranklist.php` · `contest.php` · `admin/` 등 |

## 보안상 제외

| 파일 | 이유 |
|---|---|
| `*.pem` · `*.ppk` | SSH 개인키 |
| `db.cmd` · `WinSCP.lnk` · `리눅스 명령어 모음.txt` | 서버 접속 스크립트 · 운영 메모 (외부 API 키 포함) |
| `judge/etc/judge.conf` | 채점기 운영 설정 |

> 저장소에 남은 소스의 DB 비밀번호는 `CHANGE_ME`, 서버 IP는 `<SERVER_IP>` 로 치환했습니다.

## 그 외 제외

| 파일 | 이유 |
|---|---|
| `backup/초안/` 전체 | 초기 초안 · 폐기된 페이지(`wiki.php` · `notice.php` · `contest.php` · `sample.php` · `구현.php` · `수학.php`). 최종본에 반영된 것만 `src/web/` 에 남김 |
| `공지 중간버전.txt` | 작성 중이던 공지 문구 |
| `AlgoWiki.zip` | 발표 자료(pptx) 묶음 |
| 루트의 낱개 사본 (`category.php` · `problem/`) | `backup/수정본` 과 중복. 더 완성된 쪽을 `src/web/` 에 채택 |

---

# 저장소에 없는 데이터

| 없는 것 | 어디 있었나 |
|---|---|
| 문제 본문 · 테스트 데이터 | 서비스 DB |
| 알고리즘 위키 문서 본문 | 서비스 DB (렌더링된 모습은 `assets/screenshots/wiki_disjoint_set.png`) |
| DB 스키마 덤프 | 서비스 DB |
| 수정된 `judge_client` 본체 | 배포 서버 |
