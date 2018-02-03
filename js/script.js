jQuery('#search').click(function(){
	window.location.hash = '';
	parseAJAX();
});

jQuery('.parse-table').on('click','.btn-info',function(){
	jQuery(this).closest("tr").find("textarea").length > 0 ? showHtml(this) : showEdit(this);
});

jQuery('.parse-table').on('click','.btn-success',function(){
	var tr,tds,value,textarea,i;
	var arr = [];
	tr = jQuery(this).closest('tr');
	tds = tr.find('textarea');
	selects = tr.find('select');

	if(!tds.length)
		return false;

	tds.each(function(index,element){
		value = jQuery(element).val();
		arr.push(value);
		jQuery(element).closest('td').html(value);
	});
	selects.each(function(index,element){
		value = jQuery(element).val();
		arr.push(value);
		jQuery(element).closest('td').html(value);
	});
	jQuery.ajax({
		method:'post',
		url:parse_ajax.url,
		data:{
			action:'parser_update',
			id:tr.attr('data-id'),
			num:tr.attr('data-number'),
			data:arr
		}
	}); 
});
function parseAJAX(paged){
	var type = [];
	var search = [];
	var paged = ( paged ) ? paged : 1;
	var id = jQuery('#search-by-id').val().trim();
	jQuery('.parse-table tbody,.parse-pagination').empty();
	jQuery('.post-type').each(function(index,el){
		if(jQuery(el).is(':checked'))
			type.push(el.value);
	});
	jQuery('.search-input.show .form-control').each(function(index,el){
		search.push({
			attr:jQuery(el).attr('data-type'),val:jQuery(el).val().trim()});		
	});
	jQuery.ajax({
		method:'post',
		url:parse_ajax.url,
		data:{
			action:'parser_link',
			type:type,
			search:search,
			paged:paged,
			id:id
		},
		success:function(data){
			data = JSON.parse(data);
			createParseContent(data.content);
			if(data.pagi.length > 0)
				jQuery('.parse-pagination').html(data.pagi);
		}
	});
}
function createParseContent(data){
	var tr,td_but,button_edit,button_save,a,p;
			var list = 1;
			if(data.length){
				for (var i = 0; i < data.length; i++) {
					if(data[i].links.length){
						for (var k = 0; k < data[i].links.length; k++) {
							tr = getTagElement('tr',{'data-id':data[i].id,'data-number':k});
							button_edit = getTagElement('button',{'class':'btn btn-info','type':'button','value':'edit'});
							button_save = getTagElement('button',{'class':'btn btn-success','type':'button','value':'save'}); 
							button_edit.innerHTML = '<span class="glyphicon glyphicon-pencil"></span>';
							button_save.innerHTML = '<span class="glyphicon glyphicon-floppy-disk"></span>';
							a = getTagElement('a',{'href':data[i].url},document.createTextNode(data[i].title));
							p = getTagElement('p',{},a);
							p.appendChild(getTagElement('br'));
							p.appendChild(getTagElement('span',{},document.createTextNode(data[i].url)));
							td_but = getTagElement('td',{},button_edit);
							td_but.appendChild(button_save);
							tr.appendChild(getTagElement('td',{},document.createTextNode(list)));
							tr.appendChild(getTagElement('td',{},data[i].links[k].text));
							tr.appendChild(getTagElement('td',{},document.createTextNode(data[i].links[k].url)));
							tr.appendChild(getTagElement('td',{},p));
							tr.appendChild(getTagElement('td',{},document.createTextNode(data[i].links[k].rel)));
							tr.appendChild(getTagElement('td',{},document.createTextNode(data[i].links[k].target)));
							tr.appendChild(td_but);
							list++;
							jQuery('.parse-table tbody').append(tr);
						}
					}
				}
			}
}
function setAttributes(el, attrs) {
  for(var key in attrs) {
    el.setAttribute(key, attrs[key]);
  }
  return el;
}

function getTagElement(tag,attrs,value){
	el = setAttributes(document.createElement(tag), attrs);
	if(value)
		jQuery(el).html(value);
	return el;
}

jQuery('.parse-table th span').click(function(){
	jQuery(this).siblings("div").toggleClass('show');
});

jQuery('.parse-pagination').on('click','[data-paged]',function(){
	var paged = jQuery(this).attr('data-paged');
	parseAJAX(paged);
	window.location.hash = 'page'+paged;
});

function showEdit(el){
	var tr,tds,value,textarea,select,option1,option2,option3;
	var rel = ['','nofollow','dofollow'];
	var target = ['','_blank','_self'];
	tr = jQuery(el).closest('tr');
	tds = tr.find('td');
	tds.each(function(index,element){
		if(index == 0 || index == 6 || index == 3)
			return;

		value = jQuery(element).html();
		if(index == 1 || index == 2){
			textarea = document.createElement('textarea');
			textarea.setAttribute('data-index',index);
			textarea.appendChild(document.createTextNode(value));			
			jQuery(element).html(textarea);
		}else{
			select = document.createElement('select');
			if(index == 4)
				arr = rel;
			else
				arr = target;
			for (var i = 0; i < arr.length; i++) {
				if(value == arr[i])
					select.appendChild(getTagElement('option',{'selected':'selected'},arr[i]));
				else
					select.appendChild(getTagElement('option',{},arr[i]));					
			}
			jQuery(element).html(select);
		}

	});
}

function showHtml(el){
	var tr,tds,value,textarea,i;
	var arr = [];
	tr = jQuery(el).closest('tr');
	tds = tr.find('textarea');
	selects = tr.find('select');

	if(!tds.length)
		return false;

	tds.each(function(index,element){
		value = jQuery(element).val();
		arr.push(value);
		jQuery(element).closest('td').html(value);
	});
	selects.each(function(index,element){
		value = jQuery(element).val();
		arr.push(value);
		jQuery(element).closest('td').html(value);
	});
}

jQuery('#export').click(function(){
	var trs = jQuery('.parse-table tbody tr');
	if(trs.length < 1)
		return;
	var arr = [];
	trs.each(function(index,element){
		arr.push({
			id:jQuery(element).find('td').eq(0).text(),
			anchor:jQuery(element).find('td').eq(1).text(),
			url:jQuery(element).find('td').eq(2).text(),
			page:jQuery(element).find('td').eq(3).find('span').text(),
			rel:jQuery(element).find('td').eq(4).text(),
			target:jQuery(element).find('td').eq(5).text(),
		});
	});
	jQuery.ajax({
		method:'post',
		url:parse_ajax.url,
		data:{
			action:'create_csv',
			data:arr
		},
		success:function(data){
			if(data.length == 0)
				return;
			var a = getTagElement('a',{'href':data,'download':'links_parser.csv'},'Завантажити');
			jQuery('.modal-content .modal-body').empty().append(a);
			jQuery('#showcvs').modal('show');
		}
	});
});