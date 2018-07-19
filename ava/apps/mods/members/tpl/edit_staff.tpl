	<div>
			<form id="checkout-form"  action="/members/?act=save_staff&id={id}" method="post" class="ajax smart-form" data-type="html" data-trigger="updateform" data-target="#emploers">
				<fieldset>
					<div class="row">
						<p class="ac">Фотография</p>
						<div class="staff-image">
						{IMAGE}
						</div>
					</div>

				<div class="row">
					<section class="mg30">
						<label class="label">Имя</label>
					<label class="input">
					<i class="icon-prepend fa fa-user"></i>
					<input name="name" type="text" class="w300" placeholder="Имя" value="{name}" required>
					</label>
					</section>
				</div>
				<div class="row">
					<section class="mg30">
						<label class="label">Email</label>
				<label class="input"> <i class="icon-prepend fa fa-envelope-o"></i>
					<input type="email" placeholder="E-mail" name="email" value="{email}" required>
				</label>
				</section>
				</div>
				<div class="row">
					<section class="mg30">
						<label class="label">Пароль (оставьте пустым, если не хотите изменить пароль)</label>
				<label class="input"> <i class="icon-prepend fa fa-lock"></i>
					<input type="password" placeholder="Password" name="passw" value="" >
				</label>
				</section>
				</div>
				<div class="row">
					<section class="mg30">
						<label class="label">Телефон</label>
					<label class="input"> <i class="icon-prepend fa fa-phone"></i>
						<input name="meta_phone" type="tel" data-mask="+(38) 999-9999999" value="{meta_phone}" placeholder="Телефон" required>
					</label>
				</section>
				</div>

			<div class="row">
				<section class="mg30">
					<label class="input"> Роль</label>
						<select multiple  class="select2" name="spec[]" style="width:100%;" >{SELECT_SPEC}</select>
					
				</section>
			</div>

				</fieldset>
				
			</form>	
			<div class="info"></div>
		</div>
