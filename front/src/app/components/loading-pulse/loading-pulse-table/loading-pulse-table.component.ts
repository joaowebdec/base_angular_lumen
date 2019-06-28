import { Component, Input, OnInit } from '@angular/core';

@Component({
    selector: 'loading-pulse-table',
    templateUrl  : './loading-pulse-table.component.html',
    styleUrls: ['./loading-pulse-table.component.scss']
})
export class LoadingPulseTableComponent implements OnInit
{
    
    @Input() itens: number;
    arrItens: any = [];

    ngOnInit(): void {

        for (var i = 1; i <= this.itens; i++) {

            let random = 0;
            do {
                random = Math.floor(Math.random() * 15);
            } while (random < 5);

            this.arrItens.push(random);
        }
            
    }

}