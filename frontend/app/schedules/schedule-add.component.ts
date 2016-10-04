import { Component, OnInit } from '@angular/core';
import { Router, 
		ActivatedRoute, 
		ROUTER_DIRECTIVES, 
		Location
	} from '@angular/router';
import { NgForm } from '@angular/forms';

import { SlimLoadingBarService } from 'ng2-slim-loading-bar';

import { DataService } from '../shared/services/data.service';
import { ItemsService } from '../shared/utils/items.service';
import { NotificationService } from '../shared/utils/notification.service';
import { ConfigService } from '../shared/utils/config.service';
import { MappingService } from '../shared/utils/mapping.service';
import { ISchedule, IScheduleDetails, IUser } from '../shared/interfaces';
import { DateFormatPipe } from '../shared/pipes/date-format.pipe';

@Component({
	moduleId: module.id,
    selector: 'app-schedule-add',
    templateUrl: 'schedule-add.component.html'
})
export class ScheduleAddComponent implements OnInit {
    apiHost: string;
    schedule: IScheduleDetails;
    scheduleLoaded: boolean = false;
    statuses: string[];
    types: string[];
    private sub: any;

    constructor(private route: ActivatedRoute,
        private router: Router,
        private dataService: DataService,
        private itemsService: ItemsService,
        private notificationService: NotificationService,
        private configService: ConfigService,
        private mappingService: MappingService,
        private loadingBarService:SlimLoadingBarService) { }

    ngOnInit() {
        this.apiHost = this.configService.getApiHost();
        this.loadDataInitialForAdd();
    }

    loadDataInitialForAdd() {
        this.loadingBarService.start();
        this.dataService.getDataInitial()
            .subscribe((data: IDataInitial) => {
				this.schedule = this.itemsService.getSerialized<IScheduleDetails>(data);
				//console.info(data.statuses);
                //this.schedule.statuses = data.statuses;
                this.scheduleLoaded = true;
                // Convert date times to readable format
                this.schedule.created_at = new Date(); // new DateFormatPipe().transform(schedule.timeStart, ['local']);                
                this.statuses = this.schedule.statuses;
				console.info(this.schedule);
				
				this.loadingBarService.complete();
            },
            error => {
                this.loadingBarService.complete();
                this.notificationService.printErrorMessage('Failed to load schedule. ' + error);
            });
    }

    registerSchedule(addScheduleForm: NgForm) {
        console.log(addScheduleForm.value);

        var scheduleMapped = this.mappingService.mapScheduleDetailsToSchedule(this.schedule);

        this.loadingBarService.start();
        this.dataService.registerSchedule(scheduleMapped)
            .subscribe(() => {
                this.notificationService.printSuccessMessage('Schedule has been registered');
                this.loadingBarService.complete();
				this.router.navigate(['schedules']);
            },
            error => {
                this.loadingBarService.complete();
                this.notificationService.printErrorMessage('Failed to register schedule. ' + error);
            });
    }

    

    back() {
        this.router.navigate(['/schedules']);
    }

}