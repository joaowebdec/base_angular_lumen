import { Injectable } from "@angular/core";

@Injectable({
    providedIn: 'root'
})
export class DateService {

    /**
     * Gera uma data apartir de um formato e uma data de string
     * 
     * @param format 
     * @param dateString 
     */
    generateDateByFormat(format: string, dateString: string) : Date {

        let newDate = null;
        switch (format) {
            case 'Y-m-d' :
                let arrDate = dateString.split('-');
                newDate = new Date(parseInt(arrDate[0]), parseInt(arrDate[1]) - 1, parseInt(arrDate[2]));
            break;
        }

        return newDate;

    }

}