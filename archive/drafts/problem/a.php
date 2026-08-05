<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Question Page Prototype</title>
<link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">
<script src="https://cdn.tailwindcss.com"></script>
<style>
  .question-card {
    transition: transform 0.3s;
  }
  .question-card:hover {
    transform: scale(1.03);
  }
  .tag {
    cursor: pointer;
  }
  .tag:hover {
    background-color: #e2e8f0;
  }
.custom-margin {
      margin-bottom: 150px; 
   }
</style>
</head>
<body>
<div style="display: flex;">
<div style="margin:10px; width: 350px;">
    <div class="question-card bg-white p-4 rounded shadow">   
      <h3 class="font-semibold mb-1">Hello World</h3>
      <div class="flex space-x-1 text-sm mb-2">
        <div class="tag px-2 py-1 bg-gray-200 rounded-lg">#Tag1</div>
        <div class="tag px-2 py-1 bg-gray-200 rounded-lg">#Tag2</div>
        <div class="tag px-2 py-1 bg-gray-200 rounded-lg">#Tag3</div>
      </div>
	
      <div class="flex space-x-1 text-sm custom-margin">
      <p class="text-sm mb-4">문제 설명...</p>
	</div>
	<div class="border-t pt-4"></div>
      <div class="flex items-center text-gray-600">
        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
        <span class="text-xs">대충 문제 푼사람수</span>
        <svg class="w-4 h-4 ml-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2h-2m-4 0H7a2 2 0 0 1-2-2V10a2 2 0 0 1 2-2h2m4 0h2"></path></svg>
        <span class="text-xs">제출 수?</span>
      </div>
    </div>
 </div>

<div style="margin:10px; width: 350px;">
    <div class="question-card bg-white p-4 rounded shadow">   
      <h3 class="font-semibold mb-1">Hello World</h3>
      <div class="flex space-x-1 text-sm mb-2">
        <div class="tag px-2 py-1 bg-gray-200 rounded-lg">#Tag1</div>
        <div class="tag px-2 py-1 bg-gray-200 rounded-lg">#Tag2</div>
        <div class="tag px-2 py-1 bg-gray-200 rounded-lg">#Tag3</div>
      </div>
	
      <div class="flex space-x-1 text-sm custom-margin">
      <p class="text-sm mb-4">문제 설명...</p>
	</div>
	<div class="border-t pt-4"></div>
      <div class="flex items-center text-gray-600">
        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
        <span class="text-xs">대충 문제 푼사람수</span>
        <svg class="w-4 h-4 ml-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2h-2m-4 0H7a2 2 0 0 1-2-2V10a2 2 0 0 1 2-2h2m4 0h2"></path></svg>
        <span class="text-xs">제출 수?</span>
      </div>
    </div>
 </div>

<div style="margin:10px; width: 350px;">
    <div class="question-card bg-white p-4 rounded shadow">   
      <h3 class="font-semibold mb-1">Hello World</h3>
      <div class="flex space-x-1 text-sm mb-2">
        <div class="tag px-2 py-1 bg-gray-200 rounded-lg">#Tag1</div>
        <div class="tag px-2 py-1 bg-gray-200 rounded-lg">#Tag2</div>
        <div class="tag px-2 py-1 bg-gray-200 rounded-lg">#Tag3</div>
      </div>
	
      <div class="flex space-x-1 text-sm custom-margin">
      <p class="text-sm mb-4">문제 설명...</p>
	</div>
	<div class="border-t pt-4"></div>
      <div class="flex items-center text-gray-600">
        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
        <span class="text-xs">대충 문제 푼사람수</span>
        <svg class="w-4 h-4 ml-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2h-2m-4 0H7a2 2 0 0 1-2-2V10a2 2 0 0 1 2-2h2m4 0h2"></path></svg>
        <span class="text-xs">제출 수?</span>
      </div>
    </div>
 </div>

</div>

<div style="display: flex;">
<div style="margin:10px; width: 350px;">
    <div class="question-card bg-white p-4 rounded shadow">   
      <h3 class="font-semibold mb-1">Hello World</h3>
      <div class="flex space-x-1 text-sm mb-2">
        <div class="tag px-2 py-1 bg-gray-200 rounded-lg">#Tag1</div>
        <div class="tag px-2 py-1 bg-gray-200 rounded-lg">#Tag2</div>
        <div class="tag px-2 py-1 bg-gray-200 rounded-lg">#Tag3</div>
      </div>
	
      <div class="flex space-x-1 text-sm custom-margin">
      <p class="text-sm mb-4">문제 설명...</p>
	</div>
	<div class="border-t pt-4"></div>
      <div class="flex items-center text-gray-600">
        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
        <span class="text-xs">대충 문제 푼사람수</span>
        <svg class="w-4 h-4 ml-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2h-2m-4 0H7a2 2 0 0 1-2-2V10a2 2 0 0 1 2-2h2m4 0h2"></path></svg>
        <span class="text-xs">제출 수?</span>
      </div>
    </div>
 </div>

<div style="margin:10px; width: 350px;">
    <div class="question-card bg-white p-4 rounded shadow">   
      <h3 class="font-semibold mb-1">Hello World</h3>
      <div class="flex space-x-1 text-sm mb-2">
        <div class="tag px-2 py-1 bg-gray-200 rounded-lg">#Tag1</div>
        <div class="tag px-2 py-1 bg-gray-200 rounded-lg">#Tag2</div>
        <div class="tag px-2 py-1 bg-gray-200 rounded-lg">#Tag3</div>
      </div>
	
      <div class="flex space-x-1 text-sm custom-margin">
      <p class="text-sm mb-4">문제 설명...</p>
	</div>
	<div class="border-t pt-4"></div>
      <div class="flex items-center text-gray-600">
        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
        <span class="text-xs">대충 문제 푼사람수</span>
        <svg class="w-4 h-4 ml-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2h-2m-4 0H7a2 2 0 0 1-2-2V10a2 2 0 0 1 2-2h2m4 0h2"></path></svg>
        <span class="text-xs">제출 수?</span>
      </div>
    </div>
 </div>

<div style="margin:10px; width: 350px;">
    <div class="question-card bg-white p-4 rounded shadow">   
      <h3 class="font-semibold mb-1">Hello World</h3>
      <div class="flex space-x-1 text-sm mb-2">
        <div class="tag px-2 py-1 bg-gray-200 rounded-lg">#Tag1</div>
        <div class="tag px-2 py-1 bg-gray-200 rounded-lg">#Tag2</div>
        <div class="tag px-2 py-1 bg-gray-200 rounded-lg">#Tag3</div>
      </div>
	
      <div class="flex space-x-1 text-sm custom-margin">
      <p class="text-sm mb-4">문제 설명...</p>
	</div>
	<div class="border-t pt-4"></div>
      <div class="flex items-center text-gray-600">
        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
        <span class="text-xs">대충 문제 푼사람수</span>
        <svg class="w-4 h-4 ml-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2h-2m-4 0H7a2 2 0 0 1-2-2V10a2 2 0 0 1 2-2h2m4 0h2"></path></svg>
        <span class="text-xs">제출 수?</span>
      </div>
    </div>
 </div>

</div>

<div style="display: flex;">
<div style="margin:10px; width: 350px;">
    <div class="question-card bg-white p-4 rounded shadow">   
      <h3 class="font-semibold mb-1">Hello World</h3>
      <div class="flex space-x-1 text-sm mb-2">
        <div class="tag px-2 py-1 bg-gray-200 rounded-lg">#Tag1</div>
        <div class="tag px-2 py-1 bg-gray-200 rounded-lg">#Tag2</div>
        <div class="tag px-2 py-1 bg-gray-200 rounded-lg">#Tag3</div>
      </div>
	
      <div class="flex space-x-1 text-sm custom-margin">
      <p class="text-sm mb-4">문제 설명...</p>
	</div>
	<div class="border-t pt-4"></div>
      <div class="flex items-center text-gray-600">
        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
        <span class="text-xs">대충 문제 푼사람수</span>
        <svg class="w-4 h-4 ml-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2h-2m-4 0H7a2 2 0 0 1-2-2V10a2 2 0 0 1 2-2h2m4 0h2"></path></svg>
        <span class="text-xs">제출 수?</span>
      </div>
    </div>
 </div>

<div style="margin:10px; width: 350px;">
    <div class="question-card bg-white p-4 rounded shadow">   
      <h3 class="font-semibold mb-1">Hello World</h3>
      <div class="flex space-x-1 text-sm mb-2">
        <div class="tag px-2 py-1 bg-gray-200 rounded-lg">#Tag1</div>
        <div class="tag px-2 py-1 bg-gray-200 rounded-lg">#Tag2</div>
        <div class="tag px-2 py-1 bg-gray-200 rounded-lg">#Tag3</div>
      </div>
	
      <div class="flex space-x-1 text-sm custom-margin">
      <p class="text-sm mb-4">문제 설명...</p>
	</div>
	<div class="border-t pt-4"></div>
      <div class="flex items-center text-gray-600">
        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
        <span class="text-xs">대충 문제 푼사람수</span>
        <svg class="w-4 h-4 ml-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2h-2m-4 0H7a2 2 0 0 1-2-2V10a2 2 0 0 1 2-2h2m4 0h2"></path></svg>
        <span class="text-xs">제출 수?</span>
      </div>
    </div>
 </div>

<div style="margin:10px; width: 350px;">
    <div class="question-card bg-white p-4 rounded shadow">   
      <h3 class="font-semibold mb-1">Hello World</h3>
      <div class="flex space-x-1 text-sm mb-2">
        <div class="tag px-2 py-1 bg-gray-200 rounded-lg">#Tag1</div>
        <div class="tag px-2 py-1 bg-gray-200 rounded-lg">#Tag2</div>
        <div class="tag px-2 py-1 bg-gray-200 rounded-lg">#Tag3</div>
      </div>
	
      <div class="flex space-x-1 text-sm custom-margin">
      <p class="text-sm mb-4">문제 설명...</p>
	</div>
	<div class="border-t pt-4"></div>
      <div class="flex items-center text-gray-600">
        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
        <span class="text-xs">대충 문제 푼사람수</span>
        <svg class="w-4 h-4 ml-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2h-2m-4 0H7a2 2 0 0 1-2-2V10a2 2 0 0 1 2-2h2m4 0h2"></path></svg>
        <span class="text-xs">제출 수?</span>
      </div>
    </div>
 </div>

</div>


<script>

</script>
</body>
</html>