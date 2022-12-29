import { Controller } from '@hotwired/stimulus';
import { useRegistration } from '@web-auth/webauthn-helper';


/* stimulusFetch: 'lazy' */
export default class extends Controller {
    static targets = ['message'];

    register() {

        const register = useRegistration({
            actionUrl: '/profile/security/devices/add',
            optionsUrl: '/profile/security/devices/add/options'
        });
        // We can call this login function whenever we need (e.g. form submission)
        register({})
            .then(
                (response) => window.location.href = '/'
            )
            .catch(
                (error) => error.json().then((data) => this.messageTarget.textContent = 'Authentication failure : ' + data.errorMessage)
            )
        ;

    }
}
