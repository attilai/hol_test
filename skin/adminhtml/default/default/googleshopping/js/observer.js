document.observe('click',function(e){
		
	if(e.findElement('input[type=checkbox]')){ 
		i=e.findElement('input[type=checkbox]');
		
		i.ancestors().each(function(a){
			if(a.hasClassName('fieldset')) 	selector=$(a.id);
		})
		if(selector.id=='attributes-selector'){
			if(i.checked==true)	i.ancestors()[1].select('div')[0].select('INPUT:not(INPUT[type=checkbox]),SELECT').each(function(h){h.disabled=false})
			else i.ancestors()[1].select('div')[0].select('INPUT:not(INPUT[type=checkbox]),SELECT').each(function(h){h.disabled=true})
		}
			
		i.ancestors()[1].select('li').each(function(li){
			if(i.checked==true) {
				li.select('INPUT')[0].checked=true;
			}
			else {
				li.select('INPUT')[0].checked=false;
			}
		})

		
		
		setValues(selector);
		
		
		selector.select('.selected').each(function(s){s.removeClassName('selected')})
		selector.select('.node').each(function(li){
			if(li.select('INPUT')[0].checked==true){
				li.addClassName('selected');
				
			}
		})
	}
})
document.observe('dom:loaded', function(){
	$$('.mapping').each(function(m){
		m.observe('focus',function(e){
			if(m.value.trim()==dfm.mappingStr){
				m.value='';
				m.setStyle({color:'green'})
				
			}
			setValues($('category-selector'));
		})
		m.observe('blur',function(e){
			if(m.value.trim()=='' || m.value.trim()==dfm.mappingStr){
				m.value=dfm.mappingStr;
				m.setStyle({color:'grey'})
				
			}
			setValues($('category-selector'));
		})
	})

	if($('googleshopping_categories').value!="*" && $('googleshopping_categories').value!=""){
		attributes=$('googleshopping_categories').value.evalJSON();
	
		attributes.each(function(attribute){
		
			if(attribute.checked){
				$('category_'+attribute.line).checked=true;
				$('category_'+attribute.line).ancestors()[1].addClassName('selected');
			}
			if(attribute.mapping!=""){
				$('category_'+attribute.line).next().next().value=attribute.mapping;
				$('category_'+attribute.line).next().next().setStyle({color:'green'})				
			}
			else $('category_'+attribute.line).next().next().value=dfm.mappingStr;
                        if(attribute.checked || attribute.mapping!=''){
                            $('category_'+attribute.line).ancestors()[2].visible();
                            if($('category_'+attribute.line).ancestors()[2].previous())
                                $('category_'+attribute.line).ancestors()[2].previous().select('.tree_view')[0].addClassName('open');
                        }
		});
                $$('.node').each(function(n){
                    if(n.select("ul")[0] && n.select('.tree_view.open').length<1){
                        n.select("ul")[0].hide();
                        n.select('.tree_view')[0].addClassName('close');
                    }
                    else if (n.select("ul")[0]){
                         n.select('.tree_view')[0].addClassName('open');
                    }
                })
	}
        else{
            $$('.mapping').each(function(m){
                m.value=dfm.mappingStr;
                 
            })
			$$('.node').each(function(n){
                    if(n.select("ul")[0]){
                        n.select('.tree_view')[0].addClassName('close');
                         n.select("ul")[0].hide();
                    }
                })
        }
       
        $$('.node').each(function(n){
             if(n.select('.tree_view')[0]){
                 n.select('.tree_view')[0].observe('click',function(){
                   if(n.select('.tree_view')[0].hasClassName('open')){
                        if(n.select("ul")[0]) n.select("ul")[0].hide();
                        n.select('.tree_view')[0].removeClassName('open').addClassName('close');
                    }
                    else{

                        if(n.select("ul")[0]) n.select("ul")[0].show();
                         n.select('.tree_view')[0].removeClassName('close').addClassName('open');

                    }
                })
             }
        })

	$('googleshopping_type_ids').value.split(',').each(function(e){
		$('type_id_'+e).checked=true;
		$('type_id_'+e).ancestors()[1].addClassName('selected');
	});
	
	$('googleshopping_visibility').value.split(',').each(function(e){
		$('visibility_'+e).checked=true;
		$('visibility_'+e).ancestors()[1].addClassName('selected');
	});

	attributes=$('googleshopping_attributes').value.evalJSON();

	attributes.each(function(attribute){
		
		if(attribute.checked){
			 $('attribute_'+attribute.line).checked=true;
			 $('node_'+attribute.line).addClassName('selected');
			 $('node_'+attribute.line).select('INPUT:not(INPUT[type=checkbox]),SELECT').each(function(h){h.disabled=false})
		}
		$('name_attribute_'+attribute.line).value=attribute.code;
		$('condition_attribute_'+attribute.line).value=attribute.condition;
		$('value_attribute_'+attribute.line).value=attribute.value;
	});
		
})


function setValues(selector){
	selection=new Array;
	selector.select('INPUT[type=checkbox]').each(function(i){
		if(selector.id=='attributes-selector'){
		
			attribute={}
			attribute.line=i.readAttribute('identifier');
			attribute.checked=i.checked;
			attribute.code=i.next().value;
			attribute.condition=i.next().next().value;
			attribute.value=i.next().next().next().value;
			selection.push(attribute);
		}
		else if(selector.id=='category-selector'){
			
				attribute={}
				attribute.line=i.readAttribute('identifier');
				attribute.checked=i.checked;
				attribute.mapping=i.next().next().value;
				if(attribute.mapping.trim()=="" || attribute.mapping.trim()==dfm.mappingStr ) attribute.mapping="";
				selection.push(attribute);
				
			
			
		}
		else if(i.checked==true)selection.push(i.readAttribute('identifier'));
		
	})
	switch(selector.id){
		case 'category-selector': 
			$('googleshopping_categories').value=Object.toJSON(selection);
		break;
		case 'type-ids-selector': 
			$('googleshopping_type_ids').value=selection.join(',');
		break;
		case 'visibility-selector': 
			$('googleshopping_visibility').value=selection.join(',');
		break;
		case 'attributes-selector' :
			$('googleshopping_attributes').value=Object.toJSON(selection);
		break;
	}
	
}

var dfm={
	mappingStr:">> Add Google categories",	
	/*
	 * Mise � jour des donn�es 
	 * 
	 */
	update:function(){
		
		// nom du fichier
		$('dfm-view').select('.feedname')[0].update($('googleshopping_filename').value)
		
		

		header = '<?xml version="1.0" encoding="utf-8" ?>\n';
		header+='<rss version="2.0" xmlns:g="http://base.google.com/ns/1.0">\n'; 	
		header+='<channel>\n';
		header+='<title>'+$('googleshopping_title').value+'</title>\n';
		header+='<link>'+$('googleshopping_url').value+'</link>\n';
		header+='<description>'+$('googleshopping_description').value+'</description>\n';
		
		
		
		$('dfm-view').select('._header')[0].update(dfm.enlighter(header));
		
		$('dfm-view').select('._footer')[0].update(dfm.enlighter('</channel>'))
		
		value ="<item>\n"
		value+=$('googleshopping_xmlitempattern').value+"\n";
		value+="</item>\n"
		p='<br><pre class="productpattern">'+dfm.enlighter(value)+'</pre><br>';
		
		
		
		$('dfm-view').select('._product')[0].update(p+p);
		
	},
	/*
	 * Surligenr le code
	 * 
	 */
	enlighter: function(text){
		
		
		// tags
		text=text.replace(/<([^?^!]{1}|[\/]{1})(.[^>]*)>/g,"<span class='blue'>"+"<$1$2>".escapeHTML()+"</span>")
		
		// comments
		text=text.replace(/<!--/g,"¤");
		text=text.replace(/-->/g,"¤");
		text=text.replace(/¤([^¤]*)¤/g,"<span class='green'>"+"<!--$1-->".escapeHTML()+"</span>");
		
		// php code
		text=text.replace(/<\?/g,"¤");
		text=text.replace(/\?>/g,"¤");
		text=text.replace(/¤([^¤]*)¤/g,"<span class='orange'>"+"<?$1?>".escapeHTML()+"</span>");
		// superattribut
		text=text.replace(/\{(G:[^\s}[:]*)(\sparent|\sgrouped|\sconfigurable|\sbundle)?((,)(\[.[^\]]*\]))?((,)(\[.[^\]]*\]))?((,)(\[.[^\]]*\]))?((,)(\[.[^\]]*\]))?((,)(\[.[^\]]*\]))?((,)(\[.[^\]]*\]))?\}/g,"<span class='purple'>{$1<span class='grey'>$2</span>$4<span class='green'>$5</span>$7<span class='green'>$8</span>$10<span class='green'>$11</span>$13<span class='green'>$14</span>$16<span class='green'>$17</span>$19<span class='green'>$20</span>}</span>");
		
		// attributs + 6 options 
		text=text.replace(/\{([^\s}[:]*)(\sparent|\sgrouped|\sconfigurable|\sbundle)?((,)(\[.[^\]]*\]))?((,)(\[.[^\]]*\]))?((,)(\[.[^\]]*\]))?((,)(\[.[^\]]*\]))?((,)(\[.[^\]]*\]))?((,)(\[.[^\]]*\]))?\}/g,"<span class='pink'>{$1<span class='grey'>$2</span>$4<span class='green'>$5</span>$7<span class='green'>$8</span>$10<span class='green'>$11</span>$13<span class='green'>$14</span>$16<span class='green'>$17</span>$19<span class='green'>$20</span>}</span>");
				
		// attributs + options bool
		text=text.replace(/\{([^\s}[:]*)(\sparent|\sgrouped|\sconfigurable|\sbundle)?(\?)(\[[^\]]*\])(:)(\[[^\]]*\])\}/g,"<span class='pink'>{$1<span class='grey'>$2</span>$3<span class='green'>$4</span>$5<span class='red'>$6</span>}</span>");
		
		
		
		return text;
	}
		
}

/*
 * OBSERVERS
 * 
 */
document.observe('dom:loaded', function(){
	
	
	
	page=Builder.node('div',{id:'dfm-view'},[
	                              
	       Builder.node('span',{className:'feedname'},'exemple'),
	      
	       Builder.node('div',{id:'page'},[
	         
	          Builder.node('pre',{className:'_header',name:''}),
	          Builder.node('pre',{className:'_product',name:''}),
	          Builder.node('pre',{className:'_footer',name:''})
           
           ])
    ])
    
    $('googleshopping_form').select('.hor-scroll')[0].insert({bottom:page});
	
	
	$$('.refresh').each(function(f){
		f.observe('keyup', function(){
			dfm.update()
		})
	 })
	dfm.update()
	
	
})
