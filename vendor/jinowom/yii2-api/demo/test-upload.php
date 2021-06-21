<p>user.upload : 用户上传头像1，使用file验证</p>
<form action="http://api.zzpan.net/api?v=v1&method=user.upload" method='post' enctype='multipart/form-data'>
	<input type="file" name=image >
	<button>save</button>
</form>

 
<hr>

<p>user.upload2 : 用户上传头像2,自己验证 </p>
<form action="http://api.zzpan.net/api?v=v1&method=user.upload2" method='post' enctype='multipart/form-data'>
	<input type="file" name=image >
	<button>save</button>
</form>

<hr>
<p>user.upload3: 	用户上传头像3,多文件上传,自验证 </p>
<form action="http://api.zzpan.net/api?v=v1&method=user.upload3" method='post' enctype='multipart/form-data'>
	<input type="file" name=image[] >
	<input type="file" name=image[] >
	<button>save</button>
</form>



<hr>
<p>user.upload4:	用户上传头像4,base64上传</p>
<form action="http://api.zzpan.net/api?v=v1&method=user.upload4" method='post' >
	base64string:
	<textarea type="text" name=image >data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAMAAAAoyzS7AAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAAZQTFRFF4MJAAAAMPoOJAAAAAxJREFUeNpiYAAIMAAAAgABT21Z4QAAAABJRU5ErkJggg==</textarea>

	format:
	<select name="format" id="">
		<option value="json">json</option>
		<option value="image">image</option>
	</select>
	<button>save</button>
</form>