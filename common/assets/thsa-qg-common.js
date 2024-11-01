/**
 * 
 * Javascript simple field generator
 * @since 1.1.0
 * @author Juls Terobias
 * @fork thsa
 * 
 */

/**
 * 
 * thsa_field_generator
 * @since 1.2.0
 * @param Object
 * @return Object
 * 
 */
function thsa_field_generator(args = new Object){
    
    if(Object.keys(args).length == 0)
        return;
    
    var element = document.createElement(args.type);
    var text = (args.text)? document.createTextNode(args.text) : document.createTextNode('');
    if(args.attributes){
        for(var x in args.attributes){
            element.setAttribute(args.attributes[x].attr, args.attributes[x].value);
        }
    }
    element.appendChild(text);
    if(args.type == 'select'){
        //create options
        if(args.options){
            for(var y in args.options){
                var option = document.createElement('option');
                option.value = args.options[y].value;
                option.text = args.options[y].text;
                
                var check_value = Array.isArray(args.value);
                if(check_value){
                    for(var b in args.value){
                        if(args.value[b] == args.options[y].value){
                            option.defaultSelected = true;
                        }
                    }
                }else{
                    if(args.value == args.options[y].value){
                        option.defaultSelected = true;
                    }
                }
                element.appendChild(option);
            }
        }
    }
    return element;
}