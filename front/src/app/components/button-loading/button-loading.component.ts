import { Component, OnInit, Input } from '@angular/core';

@Component({
    selector: 'button-loading',
    templateUrl  : './button-loading.component.html',
    styleUrls: ['./button-loading.component.scss']
})
export class ButtonLoadingComponent implements OnInit
{

    @Input() text: string;
    @Input() loading: boolean = false;

    ngOnInit(): void {
    }

}