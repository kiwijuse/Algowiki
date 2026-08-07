# 프론트엔드 설계 노트

> AlgoWiki의 **화면 마크업(PHP 뷰)과 CSS는 AI 도움 없이 직접 설계하고 작성**했습니다.
> 이 문서는 "무엇을 만들었나"보다 **"왜 그렇게 만들었나"** 를 남기기 위한 기록입니다.

---

## 0. 전제 조건

베이스가 된 [HUSTOJ](https://github.com/zhblue/hustoj)는 2000년대식 PHP 온라인 저지입니다.
번들러도, 컴포넌트 시스템도, 상태 관리도 없습니다. 그 위에서

- 게임 UI(퀘스트 · 상점 · 인벤토리 · 프로필 꾸미기)를 얹어야 하고
- 기존 OJ 페이지들과 **한 몸처럼 보여야** 하고
- 데스크톱과 모바일을 모두 지원해야 했습니다.

React를 얹는 선택지도 있었지만, 서버가 이미 PHP로 HTML을 만들고 있는데
그 위에 SPA를 겹치면 라우팅·인증·세션이 이중화됩니다.
그래서 **"서버가 조각 HTML을 만들고, 클라이언트는 그것을 꽂아 넣는다"** 는 한 가지 규칙으로 전체를 통일했습니다.

---

## 1. AJAX 부분 렌더링 — 이 프로젝트의 뼈대

파일 이름에 규칙이 있습니다.

| 접미사 | 역할 |
|---|---|
| `xxx.php` | 페이지 껍데기. 레이아웃 · 스타일 · 스크립트만 들고 있고, **내용은 비어 있음** |
| `xxx_db.php` | 데이터를 조회해 **완성된 HTML 조각을 echo** 하는 엔드포인트 |
| `xxx_header.php` | 페이지 상단 고정 영역 (필터 · 정렬 · 토글) |
| `xxx_ajax.php` | 폼/설정처럼 **쓰기**가 섞인 조각 렌더러 |

```
userinfo.php?tab=quest
   │
   ├─ userinfo/userinfo_quest.php   ← 껍데기 (탭 · CSS · JS)
   │      │
   │      ├─ $.post('quest/profile_db.php')  → 좌측 프로필 카드 HTML
   │      └─ $.post('quest/quest_db.php')    → 퀘스트 리스트 HTML
   │
   └─ 보상 수령 클릭 → quest/get_reward.php → 두 조각 모두 재요청
```

**얻은 것**

- 탭 전환 · 페이지네이션 · 필터 · 정렬이 전부 **새로고침 없이** 동작
- 보상을 수령하면 프로필(코인·EXP)과 퀘스트 목록 **두 군데만** 다시 그려짐
- 서버가 HTML을 만드니 클라이언트에 템플릿 엔진이 필요 없음

**대가로 감수한 것**

- HTML이 PHP 문자열 연결로 만들어져 뷰 코드가 길어짐
- 조각 단위 캐시 정책(`$cache_time`, `$OJ_CACHE_SHARE = false`)을 파일마다 직접 지정해야 함

---

## 2. JS 없이 상태를 갖는 탭

프로필 / 기록 / 퀘스트 / 상점 / 인벤토리 / 설정 — 6개 탭.
탭 상태를 JS 변수로 들지 않고 **숨긴 라디오 버튼**에 맡겼습니다.

```css
input { display: none; }                  /* 라디오 자체는 숨김 */

input:checked + label {                   /* 체크된 라디오의 라벨만 활성 스타일 */
    color: #555;
    border: 1px solid #ddd;
    border-top: 2px solid #2e9cdf;        /* 활성 탭 상단 파란 줄 */
    border-bottom: 1px solid #ffffff;     /* 아래 경계선을 흰색으로 덮어 "연결된" 느낌 */
}

#tab1:checked ~ #content1,
#tab2:checked ~ #content2 { display: block; }
```

활성 탭의 `border-bottom`을 **흰색으로 덮어** 콘텐츠 영역과 이어 붙이는 트릭이 핵심입니다.
브라우저가 상태를 들고 있으니 JS는 URL 이동만 담당합니다.

---

## 3. 육각형 퀘스트 아이콘

퀘스트 카드의 아이콘은 "테두리가 있는 육각형"입니다.
`border-radius`로는 안 되고, 이미지로 처리하면 색을 못 바꿉니다.

**두 겹의 `clip-path`** 로 해결했습니다.

```
.outer-hexagon   ← 테두리 색 배경 + 육각형 clip-path
   └ .inner-hexagon  ← 흰 배경 + 같은 모양을 살짝 작게 clip-path
        └ <img>       ← 퀘스트 아이콘
```

꼭짓점은 각지지 않게 40여 개 좌표로 라운드 처리했습니다.
덕분에 **완료 / 미완료 / 히든(`???`)** 상태를 배경색만 바꿔 표현할 수 있습니다.

<img src="../assets/screenshots/userinfo_quest.png" width="620">

---

## 4. 메인 페이지 — 좌표를 직접 잡은 카드 스택

메인의 지그재그 문제 카드는 그리드도 플렉스도 아닌 **절대배치**입니다.

```css
.p_list          { position: absolute; right: 15%; width: 37%; height: 446px; }
.problem_box     { position: absolute; width: 52%; }

.problem_box.box1 { top: -44%; left: 57%; }
.problem_box.box2 { top: -20%; left:  0%; }
.problem_box.box3 { top:   5%; left: 57%; }
.problem_box.box4 { top:  29%; left:  0%; }
.problem_box.box5 { top:  54%; left: 57%; }
```

컨테이너를 `overflow: hidden` 으로 잘라 **화면 밖으로 흘러나가는 듯한 리듬**을 만들었습니다.
좁아지면 홀수 카드를 `display: none` 으로 걷어내 2장만 남깁니다.

---

## 5. 반응형 — "좁은 화면"과 "터치 기기"는 다르다

브레이크포인트를 폭으로만 나누면 문제가 생깁니다.
**폭 800px 태블릿**과 **창을 줄인 데스크톱**은 같은 폭이지만 필요한 UI가 다릅니다.

그래서 `pointer` 미디어 특성을 함께 씁니다.

```css
@media (max-width: 991px)  { /* 좁은 데스크톱: 카드 3장 → 2장 */ }
@media (max-width: 670px)  { /* 텍스트 축소 */ }
@media (max-width: 550px)  { /* 카드 폭 고정 */ }

@media (max-width: 850px) and (pointer: coarse) {
    /* 진짜 터치 기기에서만: 카드 전부 다시 보이기, 태그 숨기기 */
    .problem_box.box1, .box3, .box5 { display: block; }
    .tag { display: none; }     /* 손가락으로는 태그 칩을 정확히 못 누름 */
}
```

- **좁은 데스크톱** → 카드를 줄인다 (마우스는 정밀하니 태그는 살린다)
- **터치 기기** → 카드는 되살리되 **태그 칩을 없앤다** (오터치 방지)

서버 쪽에서도 `isMobileDevice()` 로 User-Agent를 보고
눈 내리는 배경 같은 무거운 연출을 **아예 내려보내지 않습니다.**

---

## 6. 진행바 하나로 세 곳을 커버

레벨 EXP 바, 퀘스트 클리어 바 — 같은 3단 구조를 재사용합니다.

```
#progressContainer   회색 트랙 (radius 15px)
  ├ #progressBar     채워지는 초록 막대, width 를 % 로 서버에서 주입
  ├ #progressText    중앙 정렬된 "67.2%" 또는 "3/6"
  └ #tooltip         hover 시 "다음 레벨까지 N EXP"
```

퍼센트는 서버에서 계산해 `style="width:{$percent}%"` 로 **인라인 주입**합니다.
클라이언트가 다시 계산하지 않으니 값이 어긋날 일이 없습니다.

같은 마크업을 레벨 바(`67.2%`)와 퀘스트 바(`3/6`)에 그대로 쓰고,
가운데 텍스트 포맷만 다르게 넣었습니다.

---

## 7. 보상 수령의 손맛

게임화 사이트에서 **보상을 받는 순간**은 가장 공들여야 하는 지점입니다.

```js
function get_reward_refresh(quest_id) {
    get_reward(quest_id);              // 1. 서버에 수령 요청
    setTimeout(function () {
        firework();                    // 2. 폭죽 (canvas-confetti)
        profile_ajax();                // 3. 코인·EXP 카드 갱신
        quest_ajax();                  // 4. 퀘스트 목록 갱신
    }, 10);
}
```

폭죽은 화면 **좌우 두 지점**에서 동시에 터지고, 남은 시간에 비례해 입자 수를 줄여
자연스럽게 잦아들게 했습니다.

```js
var particleCount = 50 * (timeLeft / duration);
confetti({ ...defaults, particleCount, origin: { x: randomInRange(0.1, 0.3), ... } });
confetti({ ...defaults, particleCount, origin: { x: randomInRange(0.7, 0.9), ... } });
```

---

## 8. 잔디 · 스트릭

GitHub 잔디 형태의 일별 풀이량 히트맵을 붙였습니다.
색상은 사용자가 보유한 **스트릭 컬러 변경권** 결과에 따라 서버에서 주입됩니다.

```php
$sql = "select streak_color, nick_color, comment from users where user_id = '$user'";
```
```js
color: <?php echo $streak_color; ?>
```

닉네임 색도 같은 방식으로, **사이트 전역**(프로필 · 채점 현황 · 랭킹 · 게시판)에서 일관되게 적용됩니다.
아이템으로 얻은 색이 다른 사람 눈에도 보여야 자랑이 되기 때문입니다.

---

## 9. 디테일

**글래스모피즘 패널**

```css
.padding {
  background: rgba(255,255,255,0.6);
  backdrop-filter: blur(7px);
  border-radius: 20px;
  box-shadow: 10px -10px 20px rgb(255 255 255 / 20%),
             -10px  10px 20px rgb(255 255 255 / 10%);
  border-bottom: 3px solid rgba(255,255,255,0.4);
  border-right:  3px solid rgba(255,255,255,0.4);
  border-left:   3px solid rgba(255,255,255,0.4);
}
```

**텍스트 가독성** — 사진 위 글씨는 흰 글로우를 4겹 쌓아 배경과 분리했습니다.

```css
.new-info-text {
  text-shadow: 0 0 11px rgba(255,255,255,1), 0 0 11px rgba(255,255,255,1),
               0 0 11px rgba(255,255,255,1), 0 0 11px rgba(255,255,255,1);
}
```

**드래그 방지** — 게임 UI에서 아이콘이 드래그되면 몰입이 깨집니다.

```css
.no_drag { user-select: none; }
img      { pointer-events: none; }
```

**호버 반응** — 클릭 가능한 카드는 예외 없이 `transform: scale(1.03)` + `transition 0.3s`.
"이건 누를 수 있다"는 신호를 사이트 전체에서 동일하게 줍니다.

**시즌 연출** — 크리스마스에는 GSAP + Vue로 눈이 내리고, 로고가 산타 모자를 씁니다.
(`assets/brand/mainicon_santa.png`, `iconv_2_christmas.png`)

**폰트** — 제목은 `JalnanGothic` 웹폰트. "게임처럼"이라는 톤에 맞춰 본문 고딕과 대비를 줬습니다.

---

## 10. 돌아보며

| 잘한 선택 | 이유 |
|---|---|
| 조각 HTML + AJAX 한 가지 패턴으로 통일 | 팀원 누구나 새 화면을 같은 방식으로 붙일 수 있었음 |
| 라디오 탭 · clip-path 육각형 등 CSS로 밀어붙인 것 | JS 상태가 늘지 않아 디버깅이 단순해짐 |
| `pointer: coarse` 분기 | 실기기 테스트에서 오터치 이슈가 실제로 사라짐 |

| 다시 한다면 | 이유 |
|---|---|
| PHP 문자열 연결 대신 템플릿 분리 | 뷰 파일이 20KB를 넘어가면서 수정이 겁나기 시작함 |
| CSS를 페이지마다 인라인 `<style>`로 둔 것 | 같은 규칙이 여러 파일에 중복됨. 공통 시트로 뺐어야 함 |
| 디자인 토큰(색·간격) 미정의 | `#2e9cdf`, `#1BA0D5` 같은 값이 하드코딩으로 흩어짐 |

---

### 관련 문서

- [gamification.md](gamification.md) — 퀘스트 · 재화 · 상점 설계
- [architecture.md](architecture.md) — 채점기 훅과 스케줄러
- [file-map.md](file-map.md) — 파일별 역할
