
 <x-mail::message>
   
<p>Welcome please inter your information to login in Tinytots</p>
<p>Your account information:</p>
<ul>
    <li>Email: {{ $email }}</li>
    <li>Password: {{ $password }}</li>
</ul>
</x-mail::message>












{{--  <x-mail::message>
    
# مرحباً بك في فريقنا!

نحن سعداء جداً بانضمامك إلى كادرنا الإداري. نؤمن بأن خبراتك ومهاراتك ستكون إضافة قيمة لنا ونتطلع إلى العمل معاً لتحقيق أهدافنا.

يرجى العثور أدناه على بيانات تسجيل الدخول الخاصة بك:

email:[email@example.com](mailto:email@example.com)
password: password

<x-mail::button :url="'url-to-login-page'">
تسجيل الدخول
</x-mail::button>
شكراً جزيلاً،<br>
{{ config('app.name') }}
</x-mail::message>  --}}
