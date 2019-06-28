import { Component, Inject }              from '@angular/core';
import { FormBuilder, FormGroup } from '@angular/forms';

import { MatDialogRef, MAT_DIALOG_DATA } from '@angular/material';
import * as moment      from 'moment';

/* Services */
import { DateService }  from 'app/services/DateService';
import { UsersService } from 'app/services/users.service';
import { UrlService }   from 'app/services/url.service';


@Component({
    templateUrl  : './user-filters.component.html'
})
export class UserFiltersComponent
{

    /**
     * Formulário
     */
    form: FormGroup;

    /**
     * Flag para exibir o loading
     */
    filtering: boolean = false;

    constructor(private _fb: FormBuilder,
                private _userService: UsersService,
                private _urlService: UrlService,
                private _dialogRef: MatDialogRef<UserFiltersComponent>,
                @Inject(MAT_DIALOG_DATA) public filters: any,
                private _dateService: DateService) {

        const usedFilter = this.filters.filters;
        
        this.form = this._fb.group({
            name: [usedFilter ? usedFilter.name : ''],
            email: [usedFilter ? usedFilter.email : ''],
            status: [usedFilter ? usedFilter.status : ''],
            dateInit: [usedFilter && usedFilter.dateInit  ? this._dateService.generateDateByFormat('Y-m-d', usedFilter.dateInit) : ''],
            dateFinal: [usedFilter && usedFilter.dateFinal ? this._dateService.generateDateByFormat('Y-m-d', usedFilter.dateFinal) : '']
        });

    }

    async filter() {

        this.filtering = true;

        /* Converte as datas do moment para o padrão do mysql */
        if (this.form.value.dateInit)
            this.form.value.dateInit = moment(this.form.value.dateInit).format('YYYY-MM-DD');

        if (this.form.value.dateFinal)
            this.form.value.dateFinal = moment(this.form.value.dateFinal).format('YYYY-MM-DD');

        /* Transforma os filtros em query params e envia para o backend */
        const queryParams = this._urlService.jsonToQuery(this.form.value);        
        const users = await this._userService.get(`users?${queryParams}`);

        /* Retorna os resultados para a tela que chamou a modal */
        this._dialogRef.close({data: users, filters: this.form.value});
    }

}
