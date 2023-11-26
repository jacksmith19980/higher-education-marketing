<div class="row repeated_fields_new" id="logos_information" style="border:1px solid #ddd; padding: 10px; margin-bottom: 10px;">
	
	<div class="col-md-12">
		<div class="form-group">
    	
	        <ul class="list-unstyled">
	            <li class="media">
					<div class="media-body" style="padding-top: 15px;">
	                	
	                     <div class="input-group mb-3">
	                        <div class="custom-file">
	                          	<input type="file" class="form-control" id="logos" name="logos[]" required> 
	                          	
	                        </div>
	                    </div>
	                </div>
	            </li>
	        </ul>
		</div>
    </div>

	<div class="col-md-10">
		 
		<div class="form-group">
			
			 <select name="logo_locale[]" id="logo_locale" class="form-control form-control-lg" required>
				<option value="">Language</option>
				<option value="en" >English</option>
				<option value="fr">French</option>
				<option value="gr">German</option>			
				<option value="es">Spanish</option>
			
			</select> 

		</div> 
	
	</div>
	
<div class="col-md-2 action_wrapper_new">
<div class="form-group action_button_new">
 		<button class="btn waves-effect waves-light btn-outline-success btn-lg" id="logos_information" type="button" onclick="app.repeat_fields_new(this)"><i class="fa fa-plus"></i></button>
 
    
</div>
</div>

</div>

<div class="repeated_fields_new_wrapper" id="logos_information" data-parent="logos_information"></div>