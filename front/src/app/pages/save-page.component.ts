import { FormGroup } from '@angular/forms';

export abstract class SavePageComponent {

    /**
     * Texto do botão laoading
     */
    texto: string = 'Salvar';

    /**
     * Bloqueia o botào
     */
    loading: boolean = false;

    /**
     * Gerenciador do formulario
     */
    form: FormGroup;

    /**
     * Flag para identificar se a tela está sendo usado para insert ou update
     */
    update: boolean = false;

    /**
     * Flag para indicar que os dados estão sendo buscados
     */
    searching: boolean = false;

    /**
     * Realiza o save da entidade
     */
    abstract save();

}