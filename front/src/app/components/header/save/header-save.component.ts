import { Component, Input, EventEmitter, Output } from '@angular/core';

@Component({
    selector: 'header-save',
    templateUrl  : './header-save.component.html',
    styleUrls: ['./header-save.component.scss']
})
export class HeaderSaveComponent
{

    @Input() icon: string;
    @Input() title: string;
    @Input() subtitle: string = 'Salvar';
    @Input() loading: boolean;
    @Input() btnname: string = 'Salvar';
    @Input() update: boolean = false;
    @Output() save: EventEmitter<any> = new EventEmitter();

    ngOnInit(): void {
    }

    ngOnChanges() {
        this.btnname = this.loading ? 'Salvando...' : 'Salvar';
    }

    triggerSave() {
        this.save.emit(null);
    }

}