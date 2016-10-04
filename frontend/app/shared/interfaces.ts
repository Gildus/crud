export interface IUser {
    id: number;
    name: string;
    avatar: string;
    profession: string;
    schedulesCreated: number;
}

export interface ISchedule {
     id: number;
     names: string;
     username: string;
     email: string;
     created_at: Date;
     password: string;
     status: string;          
}

export interface IScheduleDetails {
     id: number;
     names: string;
     username: string;
     email: string;
     created_at: Date;
     password: string;
     status: string;     
     statuses: string[];     
}

export interface Pagination {
    CurrentPage : number;
    ItemsPerPage : number;
    TotalItems : number;
    TotalPages: number;
}

export class PaginatedResult<T> {
    result :  T;
    pagination : Pagination;
}

export interface Predicate<T> {
    (item: T): boolean
}

export interface IDataInitial {
	statuses: string[];     
}