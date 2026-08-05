# 저작권 및 출처 고지

AlgoWiki는 오픈소스 온라인 저지 **HUSTOJ** 를 베이스로 시작해, 그 위에 게임화 시스템과
프론트엔드 전면을 새로 얹은 프로젝트입니다.

---

## 베이스 프로젝트

### HUSTOJ

- **저장소** — https://github.com/zhblue/hustoj
- **저작권** — © zhblue and HUSTOJ contributors
- **라이선스** — GPL

AlgoWiki가 그대로 사용하거나 수정해 사용한 부분:

| 영역 | 내용 |
|---|---|
| 채점기 (`judged` / `judge_client`) | 코드 컴파일 · 실행 · 결과 판정 · 샌드박싱 전부 |
| 세션 / 인증 | 로그인 · 권한 · 관리자 구분 |
| 코어 인클루드 | `my_func.inc.php`, `memcache.php`, `bbcode.php`, `cache_start.php`, `setlang.php` 등 |
| DB 스키마 기반 | `users`, `problem`, `solution`, `contest`, `news`, `mail` 등 |
| 일부 페이지 | `help.php`, `faqs.php`, `showsource2.php`, `reinfo.php` 등을 수정해 사용 |

> 이 저장소에는 HUSTOJ 원본 소스를 **포함하지 않습니다.** 위 upstream을 참고하세요.
> `src/web/include/db_info.inc.php` 만 설정 구조를 보이기 위해 포함했으며, 자격증명은 `CHANGE_ME` 로 치환했습니다.

### SYZOJ 테마

- **저장소** — https://github.com/syzoj/syzoj
- HUSTOJ에 동봉된 SYZOJ 테마 템플릿을 출발점으로 삼아, AlgoWiki의 전 화면을 새로 작성했습니다.
- 사이트 푸터에 `AlgoWiki is powered by HUSTOJ, Theme by SYZOJ` 로 상시 표기했습니다.

---

## 라이선스

HUSTOJ가 GPL이므로, 그 파생물인 이 저장소도 **GPL-2.0** 을 따릅니다.
전문은 [LICENSE](LICENSE) 를 참고하세요.

---

## 사용한 서드파티

| 이름 | 용도 | 출처 |
|---|---|---|
| **jQuery** | AJAX · DOM 조작 | https://jquery.com |
| **Ace Editor** | 코드 제출 에디터 · 소스 뷰어 | https://ace.c9.io |
| **canvas-confetti** | 보상 수령 폭죽 연출 | https://github.com/catdad/canvas-confetti |
| **GSAP** | 시즌 눈 내리기 애니메이션 | https://gsap.com |
| **Vue 2** | 시즌 연출 렌더링 | https://vuejs.org |
| **Tailwind CSS** (CDN) | 메인 페이지 유틸리티 클래스 일부 | https://tailwindcss.com |
| **JalnanGothic (여기어때 잘난체)** | 제목 웹폰트 | 여기어때컴퍼니 · 무료 배포 |

---

## AlgoWiki 팀이 직접 만든 것

이 저장소에서 **온전히 AlgoWiki 팀의 저작물**인 부분입니다.

| 영역 | 위치 |
|---|---|
| 전 화면 프론트엔드 (PHP 뷰 · CSS · 인터랙션 JS) | `src/web/` |
| 퀘스트 진행도 훅 (C++) | `src/judge/quest_api/` |
| 일일 / 주간 퀘스트 스케줄러 (C++) | `src/judge/scheduler/` |
| 게임화 시스템 설계 (퀘스트 · 레벨 · 재화 · 상점 · 아이템) | `docs/gamification.md` |
| 아이템 확률표 · 퀴즈 틀 | `templates/` |
| 로고 · 티어 아이콘 · 퀘스트 아이콘 · 프로필 테두리 · 히어로 이미지 | `assets/` |
| 알고리즘 문제 200여 개 (출제 · 상호 검토) | *서비스 DB* |
| 태그별 알고리즘 위키 문서 | *서비스 DB* |
| 졸업 논문 | `paper/` |

---

## 이미지에 관하여

`assets/` 의 이미지는 AlgoWiki 서비스에서 실제로 사용하던 것으로, 팀이 제작하거나
생성형 도구로 만든 뒤 다듬어 사용했습니다. 다른 프로젝트에서 재사용하실 계획이라면
개별 확인 후 사용하시기 바랍니다.

---

## 제외된 파일

보안상 이 저장소에 **의도적으로 포함하지 않은** 것들입니다.

- SSH 개인키 (`*.pem`, `*.ppk`)
- 서버 접속 스크립트 · 운영 메모
- 실제 DB 자격증명 (소스 내 값은 전부 `CHANGE_ME` 로 치환)
- 외부 서비스 API 키
