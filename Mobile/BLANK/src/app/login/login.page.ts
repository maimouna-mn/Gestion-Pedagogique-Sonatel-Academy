import { Component, OnInit } from '@angular/core';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { MereService } from '../mere.service';
import { Router } from '@angular/router';

@Component({
  selector: 'app-login',
  templateUrl: './login.page.html',
  styleUrls: ['./login.page.scss'],
})
export class LoginPage implements OnInit {

  form: FormGroup
  // isEtudiant: boolean
  constructor(private formBuilder: FormBuilder, private serviceM: MereService, private router: Router) {
    this.form = this.formBuilder.group({
      email: ['', Validators.required],
      password: ['', Validators.required],
    });
    // this.isEtudiant = this.serviceM.isEtudiant()
  }
  ngOnInit() {
  }
  add() {
    console.log(this.form.value);

  }


  login() {
    this.serviceM.login(this.form.value).subscribe((result: any) => {
      if (localStorage.getItem('role') === 'etudiant') {
        console.log(result);

        const userJson = JSON.stringify(result.user)
        localStorage.setItem("data", userJson)

        this.router.navigate(['/folder/Inbox']);
      }
      return;
    });
  }

}
