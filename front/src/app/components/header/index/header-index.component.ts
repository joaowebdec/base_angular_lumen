import { Component, Input, EventEmitter, Output } from '@angular/core';

@Component({
    selector: 'header-index',
    templateUrl  : './header-index.component.html',
    styleUrls: ['./header-index.component.scss']
})
export class HeaderIndexComponent
{

    @Input() icon: string;
    @Input() title: string;
    @Input() btnname: string = 'Adicionar';
    @Output() filter: EventEmitter<any> = new EventEmitter();

    enableFilter() {
        this.filter.emit(null);
    }

}