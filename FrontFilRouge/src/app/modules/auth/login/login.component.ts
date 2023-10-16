import { Component, OnInit } from '@angular/core';
import { FormBuilder, FormGroup } from '@angular/forms';
import { Router } from '@angular/router';
import { AuthServiceService } from '../auth-service.service';

@Component({
  selector: 'app-login',
  templateUrl: './login.component.html',
  styleUrls: ['./login.component.css']
})
export class LoginComponent implements OnInit{
  constructor(private fb: FormBuilder, private authService: AuthServiceService, private router: Router) { }
  form!: FormGroup
  ngOnInit(): void {

    this.form = this.fb.group({
      email: [''],
      password: ['']
    })
  }

  login() {
    console.log(this.form.value);
    
    this.authService.login(this.form.value).subscribe((result:any) => {
      console.log(result.user);

      const userJson=JSON.stringify(result.user)
      localStorage.setItem("data", userJson)

      this.router.navigate(['/cours']);
    })
  }
}
