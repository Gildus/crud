import { Injectable } from '@angular/core';

import { ISchedule, IScheduleDetails, IUser } from '../interfaces';
import  { ItemsService } from './items.service'

@Injectable()
export class MappingService {

    constructor(private itemsService : ItemsService) { }

    mapScheduleDetailsToSchedule(scheduleDetails: IScheduleDetails): ISchedule {
        var schedule: ISchedule = {
            id: scheduleDetails.id,
            names: scheduleDetails.names,
            email: scheduleDetails.email,
            password: scheduleDetails.password,
            username: scheduleDetails.username,
            created_at: scheduleDetails.created_at,            
            status: scheduleDetails.status                        
        }

        return schedule;
    }

}