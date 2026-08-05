<?php $show_title="$MSG_FAQ - $OJ_NAME"; ?>
<?php include("../web/template/syzoj/header.php");?>
<div class="padding">
    <h1 class="ui center aligned header">위키</h1>
    <div style="font-content">
        <h2 class="ui header">검토</h2>
        <p>
            <br> C++는 <code>g++ 9.4.0</code>을 사용하여 컴파일되며 명령은 다음과 같습니다.
            &nbsp;<code>g++ -fno-asm -Wall -lm --static -O2 -std=c++14 -DONLINE_JUDGE -o Main Main.cc</code>；
            <br> C는 <code>gcc 9.4.0</code>을 사용하여 컴파일하며 명령은 다음과 같습니다.
            &nbsp;<code>gcc Main.c -o Main -fno-asm -Wall -lm --static -O2 -std=c99 -DONLINE_JUDGE</code>
            <br> <code>#pragma GCCoptimize("O0")</code>를 사용하여 O2 최적화를 수동으로 끌 수 있습니다.
            <br> Pascal은 <code>fpc 3.0.4</code>를 사용하여 컴파일되며 명령은 다음과 같습니다.
            &nbsp;<code>fpc Main.pas -oMain -O1 -Co -Cr -Ct -Ci</code>。
            <br> Java는 <code>OpenJDK 17.0.4</code>를 사용하여 컴파일되며 명령은 다음과 같습니다.
            <code> javac -J-Xms32m -J-Xmx256m Main.java</code>, 코드에 <code>공개 클래스</code>가 없으면 항목 클래스 이름을 <code>Main</code>으로 지정하세요. , 검토 당시 추가로 2초의 실행 시간과 512MB의 실행 메모리를 제공했습니다.
            <br>
            여기에 제공된 컴파일러 버전은 참조용일 뿐입니다. 실제 컴파일러 버전을 참조하세요.
        </p>
        <p><strong>표준 입력 및 출력</strong>을 사용하세요. </p>
        <h2 class="ui header">Q: cin/cout이 시간 초과(TLE)되는 이유는 무엇입니까?</h2>
        <p>A: cin/cout은 기본적으로 stdin/stdout을 동기화하고 더 많은 시스템 호출을 생성하여 성능에 영향을 주기 때문에 속도가 느려집니다. 속도를 높이려면 기본 함수 시작 부분에 다음 코드를 추가할 수 있습니다.
       <div class="ui existing segment">
            <pre style="margin-top: 0; margin-bottom: 0; ">ios::sync_with_stdio(false);
cin.tie(0);</pre>
        </div>

        * 또한, endl 대신 '\n'을 사용하십시오. endl은 기본적으로 새로 고침 작업을 증가시켜 출력 버퍼 실패를 유발하고 효율성을 저하시키기 때문입니다.
    </p>

    <h2 class="ui header">Q: gets 함수가 사라졌나요?</h2>
    <p>A: gets 함수는 입력 길이를 제한할 수 없기 때문에 역사상 다수의 버퍼 오버플로 취약점이 발생하여 최신 버전에서는 완전히 삭제되었습니다. 대신 fgets 함수를 사용해 주시기 바랍니다. 또는 대신 다음 매크로 정의를 사용하십시오.
    <div class="ques-view">   #define gets(S) fgets(S,sizeof(S),stdin)  </div>
    </p>
        <h2 class="ui header">개인정보<br></h2>
        <p>본 사이트는 아바타 저장 서비스를 제공하지 않으며, QQ 아바타 디스플레이를 사용합니다. QQ 이메일을 사용하여 등록하시면 시스템이 자동으로 QQ 아바타에 액세스합니다. </p>
        <h2 class="ui header">반환 결과 설명<br></h2>
        <div class="ques-view">
            <p>시험 문제에 대한 답변이 제출되면 채점 시스템에서 즉시 점수를 평가합니다. 각 제출 결과는 적시에 통보됩니다. 시스템에서 가능한 피드백 정보는 다음과 같습니다.</p>
            <li>평가 대기 중: 평가 시스템에서 아직 이 제출물을 평가하지 않았습니다. 잠시 기다려 주십시오.</li>
            <li>평가 중: 평가 시스템이 평가 중이며 결과는 나중에 확인할 수 있습니다.</li>
            <li>컴파일 오류: 제출한 코드를 컴파일할 수 없습니다. 컴파일러가 출력한 오류 메시지를 보려면 "컴파일 오류"를 클릭하세요.</li>
            <li>정답: 축하합니다! 이 질문을 통과하셨습니다</li>
            <li>형식 오류: 프로그램에서 출력하는 형식이 요구 사항을 충족하지 않습니다(예: 공백 및 줄 바꿈이 요구 사항과 일치하지 않음)</li>
            <li>오답: 프로그램이 평가 시스템 데이터에 대한 올바른 결과를 반환하지 못했습니다.</li>
            <li>실행 시간 초과: 프로그램이 지정된 시간 내에 실행되지 못했습니다.</li>
            <li>메모리 초과: 프로그램이 한도보다 더 많은 메모리를 사용합니다.</li>
            <li>런타임 오류: segfault, 부동 소수점 오류 등 작업 중에 프로그램이 충돌했습니다.</li>
            <li>출력이 한도를 초과함: 프로그램이 너무 많은 콘텐츠를 출력하며 이는 일반적으로 무한 루프 출력의 결과일 수 있습니다.</li>
        </div>


        <h2>프로그램 샘플</h2>
        <p>다음 샘플 프로그램을 사용하여 이 간단한 문제를 해결할 수 있습니다. <strong>2개의 정수 A와 B를 읽은 다음 그 합을 출력합니다. </strong></p>
        <p><strong>gcc (.c)</strong></p>
        <div class="ui existing segment">
            <pre style="margin-top: 0; margin-bottom: 0; ">
<code class="lang-c">#include &lt;stdio.h&gt;
int main(){
    int a, b;
    while(scanf("%d %d",&amp;a, &amp;b) != EOF){
        printf("%d\n", a + b);
    }
    return 0;
}</code></pre>
        </div>
        <p><strong>g++ (.cpp)</strong></p>
        <div class="ui existing segment">
            <pre style="margin-top: 0; margin-bottom: 0; ">
<code class="lang-c++">#include &lt;iostream&gt;
using namespace std;
int main(){
    // io speed up
    const char endl = '\n';
    std::ios::sync_with_stdio(false);
    cin.tie(nullptr);

    int a, b;
    while (cin &gt;&gt; a &gt;&gt; b){
        cout &lt;&lt; a+b &lt;&lt; endl;
    }
    return 0;
}</code></pre>
        </div>
        <p><strong>fpc (.pas)</strong></p>
        <div class="ui existing segment">
            <pre style="margin-top: 0; margin-bottom: 0; ">
<code class="lang-pascal">var
a, b: integer;
begin
    while not eof(input) do begin
        readln(a, b);
        writeln(a + b);
    end;
end.</code></pre>
        </div>
        <p><strong>javac (.java)</strong></p>
        <div class="ui existing segment">
            <pre style="margin-top: 0; margin-bottom: 0; ">
<code class="lang-java">import java.util.Scanner;	
public class Main {
    public static void main(String[] args) {
        Scanner in = new Scanner(System.in);
        while (in.hasNextInt()) {
            int a = in.nextInt();
            int b = in.nextInt();
            System.out.println(a + b);
        }
    }
}</code></pre>
        </div>
        <p><strong>python3 (.py)</strong></p>
        <div class="ui existing segment">
            <pre style="margin-top: 0; margin-bottom: 0; ">
<code class="lang-c">import io
import sys
sys.stdout = io.TextIOWrapper(sys.stdout.buffer,encoding='utf8')
for line in sys.stdin:
    a = line.split()
    print(int(a[0]) + int(a[1]))</code></pre>
        </div>
    </div>
</div>

<?php include("../web/template/$OJ_TEMPLATE/footer.php");?>
