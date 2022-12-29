import { Controller } from '@hotwired/stimulus';
import { useLogin } from '@web-auth/webauthn-helper';


/* stimulusFetch: 'lazy' */
export default class extends Controller {
    static targets = ['inputEmail', 'message'];

    login() {

        // By default the urls are "/register" and "/register/options"
        // but you can change those urls if needed.
        const login = useLogin({
            actionUrl: '/webauthn/login',
            optionsUrl: '/webauthn/login/options'
        });

        // We can call this login function whenever we need (e.g. form submission)
        login({
            username: this.inputEmailTarget.value,
        })
            .then(
                (response) => window.location.href = '/'
            )
            .catch(
                (error) => error.json().then((data) => this.messageTarget.textContent = 'Authentication failure : ' + data.errorMessage)
            )
        ;

    }
}
