! ((current, url) => {
    'use strict';

    const App = {};

    App.LoadSelect = (select) => {
        select.search.children[0].addEventListener("keyup", function(event){
            // Reset
            [].map.call(select.e.querySelectorAll("[data-select-option='add']"), function(item){
                item.parentElement.removeChild(item);
            });
            [].map.call(select.e.querySelectorAll("[data-select-optgroup='add']"), function(item){
                item.parentElement.removeChild(item);
            });

            // Options
            let options = new tail.select.options(select.e, select);
            fetch(url + "?search=" + this.value).then(resp => resp.json()).then(data => {
                Object.keys(data).forEach(role => {
                        let tmp = {};
                        tmp['option-' + data[role].id] = {
                            key: data[role].id,
                            value: data[role].name,
                            selected: current === data[role].id
                        };
                        options.add(tmp);
                });

                // Add & Query
                select.options = options;
                select.query(this.value);
            });
        });

        select.on('close', function(item, state) {
            select.e.form.submit();
        });
    };

    App.LaraPersonateInit = () => {
        String.prototype.ucwords = function () {
            return this.toLowerCase().replace(/(^([a-zA-Z\p{M}]))|([ -][a-zA-Z\p{M}])/g, $1 => $1.toUpperCase());
        };

        const button = document.getElementsByClassName('_impersonate-toggle');
        const element = document.getElementsByClassName('_impersonate-interface');
        button[0].addEventListener('click', () => element[0].classList.toggle('_impersonate-hidden'));
        document.addEventListener('DOMContentLoaded', () => {
            App.LoadSelect(tail.select(document.getElementsByClassName('_impersonate-select'), {
                    search: true,
                    width: '100%'
                })
            );
        });
    };

    App.LaraPersonateInit();

})(impersonate_current_user_id, impersonate_user_list_url);
