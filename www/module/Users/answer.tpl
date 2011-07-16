<h2>Восстановление пароля</h2>

<form action="" method="POST">
  <p style="padding-left:0;padding-bottom:10px;">Ваш секретный вопрос: {$Data.Question}</p>
  Ответ: <input type="text" name="answertext">
  <input type="submit" value="Ответить" name="answer">
  <input type="hidden" name="question" value="{$Data.Question}">
</form>