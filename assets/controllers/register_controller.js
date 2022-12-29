import { Controller } from '@hotwired/stimulus';
import { useRegistration } from '@web-auth/webauthn-helper';


/* stimulusFetch: 'lazy' */
export default class extends Controller {
    static targets = ['inputEmail', 'inputName', 'message'];

    register() {

        // By default the urls are "/register" and "/register/options"
        // but you can change those urls if needed.
        const register = useRegistration({
            actionUrl: '/webauthn/register',
            optionsUrl: '/webauthn/register/options'
        });

        register({
            username: this.inputEmailTarget.value,
            displayName: this.inputNameTarget.value
        })
            .then(
                (response) => window.location.href = '/'
            )
            .catch(
                //(error) => this.messageTarget.textContent = 'Registration failure : ' + error
                (error) => error.json().then((data) => this.messageTarget.textContent = 'Registration failure : ' + data.errorMessage)
            )
        ;

    }
}
