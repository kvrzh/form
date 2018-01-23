class FormApp {
    constructor() { // запуск приложения происходит через конструкор
        this.form = document.querySelector('#form');
        this.languages = document.querySelectorAll('.language');
        this.event();
        this.generateLang(FormApp.getParameterByName('hl'));
    }

    static getParameterByName(name, url) { // Функция для получения параметров из url
        if (!url) url = window.location.href;
        name = name.replace(/[\[\]]/g, "\\$&");
        const regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
            results = regex.exec(url);
        if (!results) return null;
        if (!results[2]) return '';
        return decodeURIComponent(results[2].replace(/\+/g, " "));
    }

    event() { // Метод, запускающий все эвенты на странице
        this.form.addEventListener('submit', (e) => {
            e.preventDefault();
            this.submitForm();
        });
        Array.prototype.forEach.call(this.form, (item) => {
            if (item.parentNode.classList[0] === 'input') {
                item.addEventListener('blur', (e) => {
                    Validate.validate(e.target);
                });
            }
        });
        Array.prototype.forEach.call(this.languages, (item) => {
            item.addEventListener('click', (e) => {
                const elem = e.target;
                if (elem.id && !elem.classList[1]) {
                    const params = {hl: elem.id};
                    this.generateLang(elem.id);
                    history.pushState(params, "", '/?hl=' + elem.id);
                    document.querySelector('.language.active').classList.remove('active');
                    elem.classList.add('active');
                }
            })
        });
    }

    submitForm() { // Метод для отправки формы
        Validate.validate(null, this.form); // Валидация при отправке
        const errors = document.querySelectorAll('div.error');
        if (errors.length === 0) {
            const formData = new FormData(this.form);
            formData.append('submit', true);
            formData.append('lang', FormApp.getParameterByName('hl'));
            const ajax = new Ajax("POST", '/form.php', formData);
            ajax.send()
                .then((result) => { // В результате приходят данные по серверной проверке
                    if (document.querySelector('.status')) document.querySelector('.status').remove();
                    const data = JSON.parse(result.data);
                    if (data.validate) {
                        const props = Object.keys(data.validate);
                        for (let i = 0, len = props.length; i < len; i++) {
                            let item = document.getElementById(props[i]);
                            if (item) {
                                Validate.setError(item, data.validate[props[i]]);
                            }
                        }
                    }
                    if (data.registration) {
                        let h3 = document.createElement('h3');
                        h3.innerHTML = data.registration.message;
                        h3.classList.add('status');
                        if (data.registration.status === true) {
                            h3.classList.add('success');
                            this.form.reset();
                        } else {
                            h3.classList.add('error');
                        }
                        const parent = this.form.parentNode;
                        parent.insertBefore(h3, parent.querySelector('form'));
                    }
                    if (data.error) {
                        const popup = document.querySelector('.popup');
                        popup.querySelector('h4').innerText = data.error;
                        popup.classList.add('flex');
                        setTimeout(() => {
                            popup.classList.add('visible');
                        }, 100);
                        popup.querySelector('button').addEventListener('click', () => {
                            location.reload();
                        });
                    }
                });
        }
    }

    generateLang(lang = 'ru') { // Метод генерации языка
        const ajax = new Ajax("GET", '/form.php?hl=' + lang);
        ajax.send()
            .then((res) => {
                if (res && res.data) {
                    localStorage.setItem("text", res.data);
                    return JSON.parse(res.data);
                }
                return false;
            })
            .then((text) => {
                if (text !== false) {
                    document.querySelector('html').lang = lang;
                    document.querySelector('.form h1').innerText = text.h1;
                    document.querySelector('title').innerText = text.title;
                    document.getElementById('login').setAttribute('placeholder', text.login.placeholder);
                    document.getElementById('password').setAttribute('placeholder', text.password.placeholder);
                    document.getElementById('repeatPassword').setAttribute('placeholder', text.repeatPassword.placeholder);
                    document.getElementById('email').setAttribute('placeholder', text.email.placeholder);
                    document.getElementById('submit').setAttribute('value', text.registration.name);
                    document.querySelector('.error-popup h2').innerText = text.error.name;
                    document.querySelector('.error-popup button').innerText = text.reboot;
                }
            });
    }
}
class Validate { // Класс валидации полей
    static validate(element = null, form = null) { //Вызов валидации, либо отдельного поля, либо всей формы
        this.text = JSON.parse(localStorage.getItem('text'));
        if (element && element !== null) {
            const parent = element.parentNode;
            if (parent.querySelector('.error')) {
                parent.querySelector('.error').remove();
                parent.classList.remove('error');
            }
            let error;
            switch (element.id) {
                case 'login' :
                    error = Validate.validateLogin(element);
                    break;
                case 'email' :
                    error = Validate.validateEmail(element);
                    break;
                case 'password' :
                    error = Validate.validatePassword(element);
                    break;
                case 'repeatPassword' :
                    error = Validate.validateRepeatPassword(element);
                    break;
                case 'image' :
                    error = Validate.validateImage(element);
                    break;
            }
            if (error && error !== true && error.length > 0) {
                this.setError(element, error);
            } else {
                parent.classList.add('success');
            }
        }
        if (form && form !== null) {
            Array.prototype.forEach.call(form, (item) => {
                if (item.parentNode.classList[0] === 'input') {
                    Validate.validate(item);
                }
            });
        }
    }

    static setError(element, message) { // Создание элемента сообщения об ошибке
        const parent = element.parentNode;
        parent.classList.add('error');
        let span = document.createElement('span');
        span.className = 'error';
        span.innerText = message;
        parent.insertBefore(span, element);
    }

    static validateLogin(element) { // Валидация логина
        let loginValue = element.value;
        if (loginValue.length === 0) {
            return this.text.login.empty;
        }
        if (loginValue.length <= 4 || loginValue.length >= 15) {
            return this.text.login.length;
        }
        if (/^[a-zA-Z0-9]+$/.test(loginValue) === false) {
            return this.text.login.type;
        }
        if (parseInt(loginValue.substr(0, 1))) {
            return this.text.login.first;
        }
        return true;
    }

    static validateEmail(element) { // Валидация email
        let emailValue = element.value;
        if (emailValue.length === 0) {
            return this.text.email.empty;
        }
        if (/^[-\w.]+@([A-z0-9][-A-z0-9]+\.)+[A-z]{2,4}$/.test(emailValue) === false) {
            return this.text.email.type;
        }
    }

    static validatePassword(element) { // Валидация пароля
        let passwordValue = element.value;
        if (passwordValue.length === 0) {
            return this.text.password.empty;
            ;
        }
        if (passwordValue.length < 6) {
            return this.text.password.length;
            ;
        }
        if (/^[a-zA-Z0-9]+$/.test(passwordValue) === false) {
            return this.text.password.type;
        }
        if (/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?!.*\s).*$/.test(passwordValue) === false) {
            return this.text.password.rule;
            ;
        }
        return true;
    }

    static validateRepeatPassword(element) { // Валидация поля "Повтор пароля"
        let repeatPasswordValue = element.value;
        if (repeatPasswordValue !== document.querySelector('#password').value) {
            return this.text.repeatPassword.compare;
        }
        if (repeatPasswordValue.length === 0) {
            return this.text.repeatPassword.empty;
        }
        return true;
    }

    static validateImage(element) { // Валидация изображения
        let imageValue = element.value;
        if (imageValue.length === 0) {
            return true;
        } else {
            let help = imageValue.split('\\');
            let imageName = help[help.length - 1].split('.');
            let format = imageName[imageName.length - 1];
            if (format && (format !== 'png' && format !== 'jpg' && format !== 'gif')) {
                return this.text.image.format;
            }
        }
    }
}
class Ajax { // Класс для работы с ajax
    constructor(method, url, data) {
        if (data) this.data = data;
        this.method = method;
        this.url = url;
    }

    send() {
        return new Promise((res, rej) => {
            const xhr = new XMLHttpRequest();
            xhr.open(this.method, this.url);
            const data = this.data ? this.data : null;
            xhr.send(data);
            xhr.onreadystatechange = () => {
                if (xhr.readyState != 4) return;
                if (xhr.status != 200) {
                    rej(xhr.status + ': ' + xhr.statusText);
                } else {
                    res({data: xhr.responseText});
                }
            }

        })
    }
}
document.addEventListener('DOMContentLoaded', () => {
    new FormApp();
});