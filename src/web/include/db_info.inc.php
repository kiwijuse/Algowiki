<?php 
//ini_set("display_errors", "Off");  //set this to "On" for debugging  ,especially when no reason blank shows up.
//error_reporting(E_ALL);
//header('X-Frame-Options:SAMEORIGIN');
//for people using hustoj out of China , be careful of the last two line of this file !
// 이 문서는 시스템 구성 파일로 전체적으로 포함되어 있으므로 수정할 때 신중하게 저장해야 하며 절대 세미콜론, 따옴표, 문법 오류가 발생하면 전체 스테이션을 열 수 없습니다.이 경우 /home/judge/src/install/fixing.으로 백업 후 본 문서를 삭제할 수 있습니다.sh 스크립트는 생성을 복구합니다.
// connect db 
static 	$DB_HOST="localhost";    //데이터베이스 서버 IP 또는 도메인 이름
static 	$DB_NAME="jol";   //데이터베이스 이름
static 	$DB_USER="hustoj";  //데이터베이스 계정
static 	$DB_PASS="CHANGE_ME";  //데이터베이스 암호

static 	$OJ_NAME="AlgoWiki";  //왼쪽 상단에 보이는 시스템명은 최대한 간결하게 하고, 중국어와 공백을 사용하지 않도록 하며, 중국어, 긴 문자열, 그림이 필요하면 template/syzoj/header.php를 직접 수정할 수 있습니다.
static 	$OJ_HOME="./";    //홈 디렉토리
static 	$OJ_ADMIN="root@localhost";  //관리자이메일
static 	$OJ_DATA="/home/judge/data";  //테스트 데이터 디렉토리
static 	$OJ_BBS=false; //'discuss3' 로 설정합니다， "bbs" for phpBB3 bridge or "discuss" for mini-forum or false for close any 
static  $OJ_ONLINE=false;  //온라인 상태 기록 여부
static  $OJ_LANG="ko";  //기본 언어
static  $OJ_SIM=false;  //유사성을 표시합니다. 표시만 됩니다. 감지 활성화 스위치는 Judge.conf에 있습니다. 직접 복사하면 표절로 간주되지 않습니다.
static  $OJ_DICT=false; //온라인 번역 표시
static  $OJ_LANGMASK=4194224; //마스크 계산기:https://pigeon-developer.github.io/hustoj-langmask/
static  $OJ_ACE_EDITOR=true;  // 강조 표시된 커밋 코드 입력 상자를 사용할지 여부
static  $OJ_AUTO_SHARE=false; //true: true로 설정하면 통과된 제목은 통계 페이지에서 다른 코드를 볼 수 있습니다.
static  $OJ_CSS="white.css";  // bing.css | kawai.css | black.css | blue.css | green.css | hznu.css
static  $OJ_SAE=false; //Sina 엔진 사용
static  $OJ_VCODE=false;  //인증 번호
static 	$OJ_REG_SPEED=0 ; //시간당 동 ip 등록 개수 제한, 0 제한 없음
static  $OJ_APPENDCODE=true;  // 코드 예약 템플릿
if (!$OJ_APPENDCODE) 	ini_set("session.cookie_httponly", 1);   // APPENDCODE 모드는 javascript가 쿠키를 조작하여 현재 언어를 저장할 수 있도록 해야 합니다.
@session_start();
static  $OJ_CE_PENALTY=false;  // 컴파일 오류에 대한 시간 제한 여부
static  $OJ_PRINTER=false;  //출력 서비스 사용하기
static  $OJ_MAIL=false; //내부 메일
static  $OJ_MARK="mark"; // 'mark' 는 정확한 점수를 나타내고, 'percent' 는 오류 비율을 나타냅니다.
static  $OJ_MEMCACHE=false;  //메모리 캐시 사용
static  $OJ_MEMSERVER="127.0.0.1";
static  $OJ_MEMPORT=11211;
static  $OJ_UDP=true;   //UDP 알림 사용하기
static  $OJ_UDPSERVER="127.0.0.1";    // 여러 판정 기계는 쉼표로 구분할 수 있으며 비표준 포트는 콜론으로 구분할 수 있습니다 $OJ_UDPSERVER="192.168.0.1,192.168.0.2,192.168.0.3:1537"; 
static  $OJ_UDPPORT=1536;
static  $OJ_JUDGE_HUB_PATH="../judge";  // UDP가 JudgeHub에 보내는 하위 경로
static  $OJ_REDIS=false;   //REDIS 대기열 사용
static  $OJ_REDISSERVER="127.0.0.1";
static  $OJ_REDISPORT=6379;
static  $OJ_REDISQNAME="hustoj";
static  $SAE_STORAGE_ROOT="http://hustoj-web.stor.sinaapp.com/";  //Sina 클라우드 스토리지 엔진
static  $OJ_CDN_URL="";  // 서버 대역폭이 작으면, 다른 사람의 동일한 버전의 OJ를 정적 자원 소스로 사용할 수 있습니다. http://cdn.hustoj.com/ 
static  $OJ_TEMPLATE="syzoj"; //template 디렉터리의 모든 하위 디렉터리는 기본 템플릿입니다. [bs3 mdui sweet syzoj mario bshark] work with discuss3
static 	$OJ_BG="/image/back_ground.jpg";  //배경 이미지의 URL을 큰따옴표로 입력하세요. 기본값은 bing이며 매 시간마다 bing.com의 최신 배경이 자동으로 변경됩니다.
static  $OJ_LOGIN_MOD="hustoj"; //include 디렉토리에서 login-xxxxxx를 설정해야 합니다.php는 다른 로그인 모듈을 호출합니다.
static  $OJ_REGISTER=true; //새 사용자 등록 허용
static  $OJ_REG_NEED_CONFIRM=false; //신규 가입자는 검토가 필요합니다.
static  $OJ_EMAIL_CONFIRM=false; //메일 활성화 계정 허용

static  $OJ_NEED_LOGIN=false; //로그인을 하셔야 접속이 가능합니다.
static  $OJ_LONG_LOGIN=false; //장시간 로그인 정보 유지 사용하기
static  $OJ_KEEP_TIME="30";  //쿠키 로그인 유효 기간 (단위: 일 (day, 이전 행이 true일 경우에만 유효)

static  $OJ_RANK_LOCK_PERCENT=0; //대회 종료 시간 비율, 예를 들어 0.2로 설정하면 5시간 게임에서는 마지막 1시간이 순위 시간이 됩니다.。
static  $OJ_RANK_LOCK_DELAY=3600; //실제 상황에 맞게 조정하고 폐회식 및 시상 후 0으로 설정하면 즉시 차단이 해제됩니다.
static  $OJ_SHOW_METAL=true; //목록에 메달을 비례적으로 표시할지 여부

static  $OJ_SHOW_DIFF=true; //WA 비교 설명 표시 여부
static  $OJ_DL_1ST_WA_ONLY=false; //첫 번째 WA의 테스트 데이터만 다운로드할 수 있는지 여부($OJ_DOWNLOAD가 켜져 있어야 함)
static  $OJ_DOWNLOAD=false; //모든 WA에 대한 테스트 데이터 다운로드 허용 여부
static  $OJ_TEST_RUN=false; //제출 인터페이스에서 테스트 실행을 허용하는지 여부
static  $OJ_MATHJAX=true;  // Mathjax 활성화
static  $OJ_BLOCKLY=false; //Blockly 인터페이스 활성화 여부 , remember to execute `wget http://dl.hustoj.com/blockly.tar.gz; tar xzf blockly.tar.gz` in /home/judge/src/web
static  $OJ_ENCODE_SUBMIT=false; //WAF 방화벽에 의한 잘못된 가로채기를 피하기 위해 base64 인코딩 제출 기능을 활성화할지 여부。
static  $OJ_OI_1_SOLUTION_ONLY=false; //대회가 마지막 제출물만 유지하는 noip 규칙을 채택하는지 여부. true인 경우 새 제출이 발생하면 이 게임의 이전 질문 제출이 삭제됩니다.
static  $OJ_OI_MODE=false; //OI 경쟁 모드를 활성화하고 순위, 상태, 통계, 사용자 정보, 내부 이메일, 포럼 등을 비활성화할지 여부.

static  $OJ_BENCHMARK_MODE=false; //이 옵션은 코드 제출에 영향을 미칩니다. 더 이상 제출 간격 제한이 없습니다. 제출 후 솔루션 ID가 반환됩니다.
static  $OJ_CONTEST_RANK_FIX_HEADER=false; //토너먼트 순위가 수평으로 스크롤될 때의 명단 수정
static  $OJ_NOIP_KEYWORD="noip";  // 제목에 이 키워드가 포함되어 있으며 noip 모드가 활성화되고 대회 중 결과가 표시되지 않으며 마지막 제출물만 유지됩니다.。
static  $OJ_NOIP_TISHI=false;  //noip 경쟁에서 true로 설정하면 noip 경쟁에서 질문 프롬프트가 표시되고 false는 프롬프트가 표시되지 않습니다.
static  $OJ_BEIAN=false;  // 등록번호가 있는 경우 등록번호를 입력하세요.
static  $OJ_RANK_HIDDEN="'admin','zhblue'";  // 관리자는 순위에 표시되지 않습니다
static  $OJ_FRIENDLY_LEVEL=0; //시스템 친화성 수준은 0~9로 잠정 설정되어 있습니다. 수준이 높을수록 시스템이 더 멍청해집니다. 시스템은 사용하기 쉽지만 보안도 저하됩니다. 이는 비전문적인 용도로만 사용됩니다. 우리는 그렇지 않습니다. 유출이나 표절에 대한 책임은 본인에게 있습니다.
static  $OJ_FREE_PRACTICE=false; //무료 연습, 대회 과제 문제에 제한 없음
static  $OJ_SUBMIT_COOLDOWN_TIME=10; //제출 냉각 시간, 두 개의 연속 제출 사이의 최소 간격(초)입니다.
static  $OJ_POISON_BOT_COUNT=10; //로봇 계정을 감염시키기 위한 시작 AC 번호입니다. 예를 들어 10으로 설정하면 10번 정답을 제출한 후에도 계정이 특정 질문을 계속 제출하는 것으로 간주되는데, 이는 로봇 동작이며 무작위 답변을 제공하기 시작합니다.
static  $OJ_MARKDOWN=true; // MARKDOWN을 켭니다. 켠 후 백그라운드에서 질문을 편집할 때 기본적으로 소스 코드 모드로 설정됩니다. [md] # 마크다운 [/md] 형식을 사용하여 마크다운 코드를 삽입합니다. []를 사용해야 하는 경우 다음을 수행할 수도 있습니다. <div class='md'> </div >를 사용하세요.
static  $OJ_INDEX_NEWS_TITLE='Algorithm Wiki';   // 해당 이름으로 공지사항 작성시 메인페이지에 출력(동일한 제목의 기사가 여러 개 있을 수 있음)
static  $OJ_DIV_FILTER=false;  // 제목에 있는 div를 필터링하고, 특히 다른 OJ 시스템의 제목에서 이상 징후를 복구합니다.。
static  $OJ_LIMIT_TO_1_IP=false;  // 사용자가 같은 시각에 하나의 IP 주소에서만 로그인할 수 있도록 제한합니다
static  $OJ_REMOTE_JUDGE=false; //Remote Judge를 사용할지 여부와 어떤 모듈을 사용할지 remote.php 설정
static  $OJ_NO_CONTEST_WATCHER=false ; //권한이 없는 사용자가 비공개 경기를 시청하는 것을 금지할지 여부
static  $OJ_CONTEST_TOTAL_100=false; //시합을 100점으로 채점하시겠습니까?
//static  $OJ_EXAM_CONTEST_ID=1000; // 시험 상태 활성화, 시험 경기 ID 작성
//static  $OJ_ON_SITE_CONTEST_ID=1000; //라이브 상태 활성화, 라이브 경기 ID 작성



/* share code */
static  $OJ_SHARE_CODE=false; // 코드 공유 기능
/* recent contest */
static  $OJ_RECENT_CONTEST=false; // "http://algcontest.rainng.com/contests.json" ; // 명문 학교 리그


static $OJ_ON_SITE_TEAM_TOTAL=0;  //비율에 따라 메달을 획득한 팀의 총 수를 계산하는 데 사용되며, 0은 목록에 표시되는 총 팀 수가 계산되고 스타 팀은 계산되지 않음을 의미합니다
static $OJ_OPENID_PWD='8a367fe87b1e406ea8e94d7d508dcf01';

/* weibo config here */
static  $OJ_WEIBO_AUTH=false;
static  $OJ_WEIBO_AKEY='1124518951';
static  $OJ_WEIBO_ASEC='df709a1253ef8878548920718085e84b';
static  $OJ_WEIBO_CBURL='http://192.168.0.108/JudgeOnline/login_weibo.php';

/* renren config here */
static  $OJ_RR_AUTH=false;
static  $OJ_RR_AKEY='d066ad780742404d85d0955ac05654df';
static  $OJ_RR_ASEC='c4d2988cf5c149fabf8098f32f9b49ed';
static  $OJ_RR_CBURL='http://192.168.0.108/JudgeOnline/login_renren.php';
/* qq config here */
static  $OJ_QQ_AUTH=false;
static  $OJ_QQ_AKEY='1124518951';
static  $OJ_QQ_ASEC='df709a1253ef8878548920718085e84b';
static  $OJ_QQ_CBURL='192.168.0.108';

/* log */
static  $OJ_LOG_ENABLED=false;
static  $OJ_LOG_DATETIME_FORMAT="Y-m-d H:i:s";
static  $OJ_LOG_PID_ENABLED=false;
static  $OJ_LOG_USER_ENABLED=false;
static  $OJ_LOG_URL_ENABLED=false;
static  $OJ_LOG_URL_HOST_ENABLED=false;
static  $OJ_LOG_URL_PARAM_ENABLED=false;
static  $OJ_LOG_TRACE_ENABLED=false;


static $OJ_SaaS_ENABLE=false;
static $OJ_MENU_NEWS=true;

require_once(dirname(__FILE__) . "/pdo.php");
require_once(dirname(__FILE__) . "/init.php");




