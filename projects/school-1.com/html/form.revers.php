<form class="revers" autocomplete="off" action='email.php' method='post'>
<div class="form-revers"> 
<fieldset>
  <!--Тег задает заголовок для групповых элементов-->
  <legend>Оставьте сообщение:</legend>
  Ваше имя: 
  <!--Устанавливает однострочное текстовое поле ввода:-->
  <input type="text" name="name">
  <!--Используется для полей ввода, которые должны содержать адрес электронной почты.-->
  E-mail:
  <input type="text" name="email">
  Номер телефона:
  <input type="text" name="phone">
    </br>
  Сообщение:
    </br>
  <!--Тег разрешает многострочный ввод текста.-->
  <textarea rows="10" cols="45" name="message"></textarea> </br>
  <!--Устанавливает кнопку для отправки данных формы в обработчик формы.-->
  <input type="submit" value="Отправить сообщение">
  </fieldset>
</div>
</form>

