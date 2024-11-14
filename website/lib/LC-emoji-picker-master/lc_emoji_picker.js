/**
 * lc_emoji_picker.js - Fancy emoji picker for text inputs and textareas
 * Version: 1.1.1
 * Author: Luca Montanari (LCweb)
 * Website: https://lcweb.it
 * Licensed under the MIT license
 */


(function() { 
	"use strict";
    if(typeof(window.lc_emoji_picker) != 'undefined') {return false;} // prevent multiple script inits    
    
    /*** vars ***/
    let emoji_json          = false,
        window_width        = null,
        style_generated     = null,
        active_trigger      = null,
        active_sel_cb       = null,
        cat_waypoints       = {};
    
    const category_icons = {
        "smileys--people" : "😀", 
        "animals--nature" : "🐇",
        "travel--places"  : "🚘",
        "activities"      : "⚽",
        "objects"         : "🎧",
        "symbols"         : "🈶",
        "flags"           : "🚩",
    };
    
    const def_opts = {
        picker_trigger : 
        '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 25 25"><path d="M12.5,0A12.5,12.5,0,1,0,25,12.5,12.52,12.52,0,0,0,12.5,0ZM23,12.5A10.5,10.5,0,1,1,12.5,2,10.5,10.5,0,0,1,23,12.5Z"/><path d="M17.79,15h0a1.1,1.1,0,0,0-1.34.12,4,4,0,0,0-.3.28l-.06.05a4.83,4.83,0,0,1-.42.37,5.06,5.06,0,0,1-5.41.57,5.12,5.12,0,0,1-1.61-1.19A1.14,1.14,0,1,0,7,16.75a7.36,7.36,0,0,0,5.49,2.37h.63a3.15,3.15,0,0,0,.37,0A7.41,7.41,0,0,0,18,16.74a1.34,1.34,0,0,0,.32-.58A1.09,1.09,0,0,0,17.79,15Z"/><path d="M7.44,10.18l0-.19c0-.15.1-.34.48-.47a1.1,1.1,0,0,1,1,.09.61.61,0,0,1,.31.51,1,1,0,0,0,1,.88h.08a1,1,0,0,0,1-1.06A2.84,2.84,0,0,0,8.26,7.5L8,7.53a4.85,4.85,0,0,0-.53.08A2.64,2.64,0,0,0,5.33,9.94a1,1,0,0,0,1,1.06A1.07,1.07,0,0,0,7.44,10.18Z"/><path d="M16.56,7.51h0A3,3,0,0,0,14,8.89a1.78,1.78,0,0,0-.3,1.31,1,1,0,0,0,1,.8,1.06,1.06,0,0,0,1-.7,1.7,1.7,0,0,0,.06-.31.69.69,0,0,1,.58-.5,1.07,1.07,0,0,1,1,.23.6.6,0,0,1,.17.4,1,1,0,0,0,1.15.87,1,1,0,0,0,1-1.06C19.62,8.55,18.27,7.51,16.56,7.51Z"/></svg>', // (string) html code injected as picker trigger  
        
        trigger_position : { // (object) defines trigger position relatively to target field
            top : '5px',
            right: '5px',
        },
        trigger_size : { // (object) defines trigger size
            height : '22px',
            width: '22px',
        },
        target_r_padding    : 27, // (int) right padding value (in pixels) applied to target field to avoid texts under the trigger
        emoji_json_url      : 'https://raw.githubusercontent.com/LCweb-ita/LC-emoji-picker/master/emoji-list.min.json', // (string) emoji JSON url
        trigger_title       : 'insert emoji',
        
        labels : [ // (array) option used to translate script texts
            'insert emoji',
            'search emoji',
            '.. no results ..',
        ],
        
        selection_callback  : null, // function(emoji, target_field) {}, - triggered as soon as an emoji is selected. Passes emoji and target field objects as parameters
    };
    
    
    
    
    
    /*** hide picker cicking outside ***/
    document.addEventListener('click', function(e) {
        const picker = document.querySelector("#lc-emoji-picker.lcep-shown");
        if(!picker || e.target.classList.contains('lcep-trigger')) {
            return true;    
        }
        
        // is an element within a trigger?
        for (const trigger of document.getElementsByClassName('lcep-trigger')) {
            if(trigger.contains(e.target)) {
                return true; 
            }    
        }
        
        // close if clicked elementis not in the picker
        if(!picker.contains(e.target) && !e.target.classList.contains('lcep-shown')) {
            picker.classList.remove('lcep-shown');
            active_trigger = null;
            active_sel_cb = null;
        }
        return true;
    });
    
    
    /* hide picker on screen resizing */
    window.addEventListener('resize', function(e) {
        const picker = document.querySelector("#lc-emoji-picker.lcep-shown");
        if(!picker || window_width == window.innerWidth) {
            return true;    
        }
        
        // close if clicked elementis not in the picker
        picker.classList.remove('lcep-shown');
        active_trigger = null;
        active_sel_cb = null;
        
        return true;
    });
    
    
    
    
   
    
    /*** plugin class ***/
    window.lc_emoji_picker = function(attachTo, options = {}) {
    
        this.attachTo = attachTo;
        if(!this.attachTo) {
            return console.error('You must provide a valid selector string first argument');
        }
    
    
        // override options
        if(typeof(options) !=  'object') {
            return console.error('Options must be an object');    
        }
        options = Object.assign({}, def_opts, options);
    
        
        /* initialize */
        this.init = function() {
            const $this = this;
            
            // Generate style
            if(!style_generated) {
                this.generate_style();
                style_generated = true;
            }

            // load emoji json data on page loaded - stop plugin execution until it is loaded
            if(typeof(emoji_json) != 'object') {
                document.addEventListener("DOMContentLoaded", () => {this.fetch_emoji_data()});
                return true;
            }
            
            
            // assign to each target element
            maybe_querySelectorAll(attachTo).forEach(function(el) {
                if(
                    (el.tagName != 'TEXTAREA' && el.tagName != 'INPUT') ||
                    (el.tagName == 'INPUT' && el.getAttribute('type') != 'text')
                ) {
                    return;    
                }
                
                // do not initialize twice
                if(el.parentNode.classList.length && el.parentNode.classList.contains('lcep-el-wrap')) {
                    return;    
                }
  
                $this.append_emoji_picker();
                $this.wrap_element(el);
                
                document.querySelector('.lcep-search input').addEventListener("keyup", (e) => {
                    $this.emoji_search(e)    
                });
            });
        };
    
        
        
        /* emoji search - e = event */
        this.emoji_search = function(e) {
            const parent    = e.target.parentNode,
                  val       = e.target.value,
                  categories= document.querySelectorAll('#lc-emoji-picker .lcep-category'),
                  emojis    = document.querySelectorAll('#lc-emoji-picker .lcep-all-categories li');
            
            if(val.length < 2) {
                for(const emoji of emojis) {        
                    emoji.classList.remove('lcep-hidden-emoji');
                    
                    parent.classList.remove('lcep-searching');
                }
            }
            else {
                for(const emoji of emojis) {     
                    (emoji.getAttribute('data-name').match(val)) ? emoji.classList.remove('lcep-hidden-emoji') : emoji.classList.add('lcep-hidden-emoji');        
                }   
                
                parent.classList.add('lcep-searching');
            }  
            
            
            for(const cat of categories) {
                (cat.querySelectorAll('li:not(.lcep-hidden-emoji)').length) ? cat.classList.remove('lcep-hidden-emoji-cat') : cat.classList.add('lcep-hidden-emoji-cat');     
            }

            if(!document.querySelectorAll('.lcep-all-categories ul:not(.lcep-hidden-emoji-cat)').length) {
                if(!document.querySelector('.lcep-no-results')) {
                    document.querySelector('.lcep-all-categories').insertAdjacentHTML('beforeend', '<em class="lcep-no-results">'+ options.labels[2] +'</em>');
                }
            } 
            else if(document.querySelector('.lcep-no-results')) {
                document.querySelector('.lcep-no-results').remove();    
            }
        };
        
        
        
        /* clear emoji search */
        this.clear_search = function() {
            const input = document.querySelector('.lcep-search input');

            input.value = '';
            input.dispatchEvent(new Event('keyup'));
        };

        
        
        /* go to emoji category by clicking btn */
        this.go_to_emoji_cat = function(el, cat_id) {
            const top_pos = document.querySelector(".lcep-category[category-name='"+ cat_id +"']").offsetTop;
            document.querySelector('.lcep-all-categories').scrollTop = top_pos - 100;
            
            document.querySelector("li.lcep-active").classList.remove('lcep-active');
            el.classList.add('lcep-active');
        };
        
        
        
        /* select emoji cat on emojis scroll */
        this.cat_waypoints_check = function() {
            if(!document.querySelector('.lcep-shown')) {
                return true;    
            }

            const top_scroll = document.querySelector('.lcep-all-categories').scrollTop,
                  keys = Object.keys(cat_waypoints);
            
            keys.sort().reverse();
            
            let active = keys[0];
            for(const val of keys) {
                if(top_scroll >= parseInt(val, 10)) {
                    active = val;
                    break;
                }
            }
            
            const cat_id = cat_waypoints[active];
            
            document.querySelector("li.lcep-active").classList.remove('lcep-active');
            document.querySelector(".lcep-categories li[data-index='"+ cat_id +"']").classList.add('lcep-active');
        };
        
        
        
        /* reset picker: clear search and scrollers */
        this.reset_picker = function() {
            document.querySelector('.lcep-search i').click();
            document.querySelector('.lcep-categories li').click();
        };
        
        
        
        /* show picker */
        this.show_picker = function(trigger) {
            const picker = document.getElementById('lc-emoji-picker');
            window_width = window.innerWidth;
            
            if(trigger == active_trigger) {
                picker.classList.remove('lcep-shown');
                active_trigger = null;
                active_sel_cb = null;
                return false;
            }

            this.reset_picker();
            active_trigger = trigger;
            active_sel_cb = options.selection_callback; 
                         
            const   picker_w    = picker.offsetWidth,
                    picker_h    = picker.offsetHeight,
                    at_offsety  = active_trigger.getBoundingClientRect(),
                    at_h        = parseInt(active_trigger.clientHeight, 10) + parseInt(getComputedStyle(active_trigger)['borderTopWidth'], 10) + parseInt(getComputedStyle(active_trigger)['borderBottomWidth'], 10),
                    y_pos       = (parseInt(at_offsety.y, 10) + parseInt(window.pageYOffset, 10) + at_h + 5);

            // left pos control - also checking side overflows
            let left = (parseInt(at_offsety.right, 10) - picker_w);
            if(left < 0) {
                left = 0;
            }
            
            // mobile? show it centered
            if(window.innerWidth < 700) {
                left = Math.floor( (window.innerWidth - picker_w) / 2);    
            }
            
            // top or bottom ?   
            const y_pos_css = (y_pos + picker_h - document.documentElement.scrollTop < window.innerHeight) ? 
                    'top:'+ y_pos : 
                    'transform: translate3d(0, calc((100% + '+ (active_trigger.offsetHeight + 10) +'px) * -1), 0); top:'+ y_pos; 

            picker.setAttribute('style', y_pos_css +'px; left: '+ left +'px;');  
            picker.classList.add('lcep-shown');
        };
        
        
        
        /* select emoji and insert it in the field */
        this.emoji_select = function(emoji) {
            const field = active_trigger.parentNode.querySelector('input, textarea');
            
            // wordpress implementations fix (auto injecting emoji images into selectors)
            const true_emoji = (emoji.getElementsByTagName('IMG').length) ? emoji.getElementsByTagName('IMG')[0].getAttribute('alt') : emoji.innerText;     
            field.value = field.value + true_emoji;
            
            if(active_sel_cb && typeof(active_sel_cb) == 'function') {
                active_sel_cb.call(this, emoji, field);    
            }
        };
        
        
        
        /* wrap target element to allow trigger display */
        this.wrap_element = function(el) {
            const uniqid = Math.random().toString(36).substr(2, 9);
            
            let trigger_css = '';
            const trigger_css_props = {...options.trigger_position, ...options.trigger_size};
            
            Object.keys(trigger_css_props).some(function(name) {
                trigger_css += name +':'+ trigger_css_props[name] +';';     
            });
            
            let div = document.createElement('div');
            div.setAttribute('data-f-name', el.getAttribute('name'));
            div.classList.add("lcep-el-wrap");
            
            div.innerHTML = 
                '<span id="'+ uniqid +'" class="lcep-trigger" style="'+ trigger_css +'" title="'+ options.labels[0] +'">'+ 
                options.picker_trigger +'</span>';
            
            el.parentNode.insertBefore(div, el);
            div.appendChild(el);
            
            // event to show picker
            const trigger = document.getElementById(uniqid);
            trigger.addEventListener("click", (e) => {this.show_picker(trigger)}); 
        };
        
        

        /* fetches emoji JSON data */
        this.fetch_emoji_data = function() {
            
            // avoid multiple fetcheings and wait for it
            if(typeof(emoji_json) == 'object') {
                this.init();
                return true;
            }
            if(emoji_json == 'loading') {
                const that = this;
                
                setTimeout(function() {
                    that.fetch_emoji_data();
                }, 50);
                
                return true;
            }
            
            emoji_json = 'loading';
            
            fetch(options.emoji_json_url)
                .then(response => response.json())
                .then(object => {
                    emoji_json = object;
                    this.init();
                })
                .catch(function(err) {
                    emoji_json = false;
                });
        };
    
        
        
        /* append emoji container picker to the body */
        this.append_emoji_picker = function() {
            if(document.getElementById("lc-emoji-picker")) {
                return true;
            }
            
            let picker = `
            <div id="lc-emoji-picker">
                <div class="lcep-categories">%categories%
                    <div class="lcep-search">
                        <input placeholder="${ options.labels[1] }" />
                        <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 512.005 512.005" xml:space="preserve"><g><g><path d="M505.749,475.587l-145.6-145.6c28.203-34.837,45.184-79.104,45.184-127.317c0-111.744-90.923-202.667-202.667-202.667S0,90.925,0,202.669s90.923,202.667,202.667,202.667c48.213,0,92.48-16.981,127.317-45.184l145.6,145.6c4.16,4.16,9.621,6.251,15.083,6.251s10.923-2.091,15.083-6.251C514.091,497.411,514.091,483.928,505.749,475.587z M202.667,362.669c-88.235,0-160-71.765-160-160s71.765-160,160-160s160,71.765,160,160S290.901,362.669,202.667,362.669z"/></g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g></svg>
                        <i>×</i>
                    </div>
                </div>
                <div>%pickerContainer%</div>
            </div>`;

            let categories      = '<ul>%categories%</ul>',
                categoriesInner = ``,
                outerUl         = `<div class="lcep-all-categories">%outerUL%</div>`,
                innerLists      = ``,
                
                index           = 0,
                object          = emoji_json; // Loop through emoji object

            for (const key in object) {
                if (object.hasOwnProperty(key)) {
                    
                    // Index count
                    index++;
                    let keyToId = key.split(' ').join('-').split('&').join('').toLowerCase();
 
                    const categories = object[key];
                    categoriesInner += `
                    <li class="${(index === 1) ? 'lcep-active' : ''}" data-index="${keyToId}" title="${key}">
                        <a href="javascript:void(0)">${category_icons[keyToId]}</a>
                    </li>`;

                    innerLists += `
                    <ul class="lcep-category" category-name="${keyToId}">
                        <div class="lcep-container-title">${key}</div>
                        <div class="lcep-grid">`;

                            // Loop through emoji items
                            categories.forEach(item => {
                                innerLists += `
                                <li data-name="${item.description.toLowerCase()}">
                                    <a class="lcep-item" title="${item.description}" data-name="${item.description.toLowerCase()}" data-code="${item.code}" href="javascript:void(0)">${item.emoji}</a>
                                </li>`;
                            });

                        innerLists += `
                        </div>
                    </ul>`;
                }
            }
            
            let allSmiles   = outerUl.replace('%outerUL%', innerLists),
                cats        = categories.replace('%categories%', categoriesInner);

            picker = picker.replace('%pickerContainer%', allSmiles).replace('%categories%', cats);
            document.body.insertAdjacentHTML('beforeend', picker);
           
            
            // bind cat naviagation
            for (const cat of document.querySelectorAll('.lcep-categories li')) {
                cat.addEventListener("click", (e) => {
                    this.go_to_emoji_cat(cat, cat.getAttribute('data-index'));
                });    
            }
            
            // set save waypoints for scrolling detection
            for (const cat_tit of document.querySelectorAll('.lcep-container-title')) {
                cat_waypoints[ cat_tit.offsetTop - 112 ] = cat_tit.parentNode.getAttribute('category-name');
            }
            
            let scroll_defer = false;
            document.querySelector('.lcep-all-categories').addEventListener("scroll", () => {
                if(scroll_defer) {
                    clearTimeout(scroll_defer);     
                }
                scroll_defer = setTimeout(() => {
                    this.cat_waypoints_check();
                }, 50);
            });
            
            // bind search
            document.querySelector('.lcep-search i').addEventListener("click", (e) => {this.clear_search()});
            
            // emoji selection
            for (const emoji of document.querySelectorAll('.lcep-all-categories li')) {
                emoji.addEventListener("click", (e) => {this.emoji_select(emoji)});
            }
        };
        
        
        
        /* creates inline CSS into the page */
        this.generate_style = function() {        
            document.head.insertAdjacentHTML('beforeend', 
`<style>
.lcep-el-wrap {
    position: relative;
    width: 100%;
}
.lcep-el-wrap > textarea,
.lcep-el-wrap > input {
    padding-right: ${options.target_r_padding}px;
    width: 100%;
}
.lcep-trigger {
    display: inline-block;
    position: absolute;
    cursor: pointer;
}
.lcep-trigger svg {
    width: 100%;
    height: 100%;
    border-radius: 50%;
    border: 2px solid transparent;
    opacity: 0.8;
    fill: #282828;
    transition: all .15s ease;
}
.lcep-trigger svg:hover {
    fill: #202020;
}
#lc-emoji-picker,
#lc-emoji-picker * {
    box-sizing: border-box;
}
#lc-emoji-picker {
    visibility: hidden;
    z-index: -100;
    opacity: 0;
    position: absolute;
    top: -9999px;
    z-index: 999;
    width: 280px;
    min-height: 320px;
    background: #fff;
    box-shadow: 0px 2px 13px -2px rgba(0, 0, 0, 0.18);
    border-radius: 6px;
    overflow: hidden;
    border: 1px solid #ccc;
    transform: scale(0.85);
    transition: opacity .2s ease, transform .2s ease;
}
#lc-emoji-picker.lcep-shown {
    visibility: visible;
    z-index: 999;
    transform: none;
    opacity: 1;

}
#lc-emoji-picker .lcep-all-categories {
    height: 260px;
    overflow-y: auto;
    padding: 0 5px 20px 10px;
}
#lc-emoji-picker .lcep-category:not(:first-child) {
    margin-top: 22px;
}
#lc-emoji-picker .lcep-container-title {
    color: black;
    margin: 10px 0;
    text-indent: 10px;
    font-size: 13px;
    font-weight: bold;
}
#lc-emoji-picker * {
    margin: 0;
    padding: 0;
    text-decoration: none;
    color: #666;
    font-family: sans-serif;
    user-select: none;
    -webkit-tap-highlight-color:  rgba(255, 255, 255, 0); 
}
.lcep ul {
    list-style: none;
    margin: 0;
    padding: 0;
}
.lcep-grid {
    display: flex;
    flex-wrap: wrap;
}
.lcep-grid > li {
    cursor: pointer;
    flex: 0 0 calc(100% / 6);
    max-width: calc(100% / 6);
    height: 41px;
    min-width: 0;
    display: flex;
    justify-content: center;
    align-items: center;
    background: #fff;
    border-radius: 2px;
    transition: all .2s ease;
}
.lcep-grid > li:hover {
    background: #99c9ef;
}
ul.lcep-hidden-emoji-cat,
.lcep-grid > li.lcep-hidden-emoji {
    display: none;
}
.lcep-grid > li > a {
    display: block;
    font-size: 21px;
    margin: 0;
    padding: 22px 0px;
    line-height: 0;
}
.lcep-categories ul {
    display: flex;
    flex-wrap: wrap;
    list-style: none;
}
.lcep-categories li {
    transition: all .3s ease;
    flex: 0 0 calc(100% / 7);
    display: flex;
    max-width: calc(100% / 7);
}
.lcep-categories li.lcep-active {
    box-shadow: 0 -3px 0 #48a6f0 inset;
}
.lcep-categories a {
    padding: 7px !important;
    font-size: 19px;
    height: 42px;
    display: flex;
    text-align: center;
    justify-content: center;
    align-items: center;
    position: relative;
    filter: grayscale(100%) contrast(150%);
}
.lcep-categories a:before {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(255, 255, 255, .2);
    cursor: pointer;
    transition: background .25s ease;
}
.lcep-categories li:not(.lcep-active):hover a:before {
    background: rgba(255, 255, 255, .4);
}
.lcep-search {
    position: relative;
    border-top: 1px solid #ddd;
    padding: 10px 6px !important;
}
.lcep-search input {
    width: 100%;
    border: none;
    padding: 8px 30px 8px 10px !important;
    outline: none;
    background: #fff;
    font-size: 13px;
    color: #616161;
    border: 2px solid #ddd;
    height: 30px;
    border-radius: 25px; 
    user-select: auto !important;
}
.lcep-search svg,
.lcep-search i {
    width: 14px;
    height: 14px;
    position: absolute;
    right: 16px;
    top: 18px;
    fill: #444;
    cursor: pointer;
}
.lcep-search i {
    color: #444;
    font-size: 22px;
    font-family: arial;
    line-height: 14px;
    transition: opacity .15s ease;
}
.lcep-search i:hover {
    opacity: .8;
}
.lcep-searching svg,
.lcep-search:not(.lcep-searching) i {
    display: none;
}
#lc-emoji-picker img.emoji {
    min-width: 23px;
    height: auto !important;
}
#lc-emoji-picker .lcep-no-results {
	font-size: 90%;
	display: block;
	text-align: center;
	margin-top: 1em;
}
</style>`);
        };
        

        // init when called
        this.init();
    };
    
    
    
    
    
    
    // UTILITIES
    
    // sanitize "selector" parameter allowing both strings and DOM objects
    const maybe_querySelectorAll = (selector) => {
             
        if(typeof(selector) != 'string') {
            if(selector instanceof Element) { // JS or jQuery 
                return [selector];
            }
            else {
                let to_return = [];
                
                for(const obj of selector) {
                    if(obj instanceof Element) {
                        to_return.push(obj);    
                    }
                }
                return to_return;
            }
        }
        
        // clean problematic selectors
        (selector.match(/(#[0-9][^\s:,]*)/g) || []).forEach(function(n) {
            selector = selector.replace(n, '[id="' + n.replace("#", "") + '"]');
        });
        
        return document.querySelectorAll(selector);
    };
    
})();