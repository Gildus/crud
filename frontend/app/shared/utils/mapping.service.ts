import { Injectable } from '@angular/core';

import { IUser, IUserDetails } from '../interfaces';
import  { ItemsService } from './items.service'

@Injectable()
export class MappingService {

    constructor(private itemsService : ItemsService) { }

    mapUserDetailsToUser(details: IUserDetails): IUser {
        var res: IUser = {
            id: details.id,
            names: details.names,
            email: details.email,
            password: details.password,
            username: details.username,
            created_at: details.created_at,            
            status: details.status                        
        }

        return res;
    }

}