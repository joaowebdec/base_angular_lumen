import { ValidatorFn, AbstractControl } from "@angular/forms";

export function confirmPassword(field: string = 'password') : ValidatorFn { 

    return (control: AbstractControl) => {

        let confirmValue = control.value;
        
        if (control.parent) {
            let passwordValue = control.parent.controls[field].value;
            
            if (confirmValue != passwordValue)
                return {confirmPassword: true};

        }
        
        return null;

    }
    
}