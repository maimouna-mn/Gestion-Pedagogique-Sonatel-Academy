import { Injectable } from '@angular/core';
import { MereServiceService } from 'src/app/mere-service.service';

@Injectable({
  providedIn: 'root'
})
export class SessionService extends MereServiceService<any>{
  override getUri(): string {
    return 'session';
  }

}
