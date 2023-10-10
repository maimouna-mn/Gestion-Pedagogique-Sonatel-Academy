import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { Observable } from 'rxjs';
import { environment } from 'src/environnement/environment';

@Injectable({
  providedIn: 'root'
})
export abstract class MereServiceService<T> {


  constructor(private http:HttpClient) { }

  abstract getUri():string

  all<U>(): Observable<U> {
    const uri = this.getUri()
    return this.http.get<U>(`${environment.apiUrl}/${uri}`)
  }
  all1(page: number): Observable<any> {
    const uri = this.getUri();
    // Utilisez le paramètre 'page' pour spécifier la page actuelle
    return this.http.get<any>(`${environment.apiUrl}/${uri}?page=${page}`);
  }

  // allSelect<U>(): Observable<U> {
  //   const uri = this.getUri()
  //   return this.http.get<U>(`${environment.apiUrl}/${uri}/all`)
  // }


  // index<U>():Observable<U>{
  //   const uri=this.getUri()
  //  return this.http.get<U>(`${environment.apiUrl}/${uri}`)
  // }

  // recherche<U>(code: any): Observable<U> {
  //   const uri = this.getUri()
  //   return this.http.get<U>(`${environment.apiUrl}/${uri}/recherche/${code}`);
  // }

  store<U>(produit: T): Observable<U> {
    const uri = this.getUri()
    return this.http.post<U>(`${environment.apiUrl}/${uri}`,produit);
  }
  
  filtre<U>(id: number): Observable<U> {
    const uri = this.getUri()

    return this.http.get<U>(`${environment.apiUrl}/${uri}/filtre/${id}`)
  }
  // index<U>(page: number, size: number): Observable<U> {
  //   const uri = this.getUri()
  //   return this.http.get<U>(`${environment.apiUrl}/${uri}/index?page=${page}&size=${size}`);
  // }

  update<U>(article: T, id: number): Observable<U> {
    const uri = this.getUri()
    return this.http.put<U>(`${environment.apiUrl}/${uri}/${id}`, article)
  }

  delete<U>(id: number): Observable<U> {
    const uri = this.getUri()

    return this.http.delete<U>(`${environment.apiUrl}/${uri}/${id}`)
  }
}
