import { Component, OnInit } from '@angular/core';

@Component({
  selector: 'app-team-profile',
  templateUrl: './team-profile.page.html',
  styleUrls: ['./team-profile.page.scss'],
})
export class TeamProfilePage implements OnInit {

  constructor() { }

  ngOnInit() {
    let team = location.search.split('id=')[1];
    /*
    for (var i : ng.document.getElementsByClassName('data-teamnum')){
      i.innerText = team;
    }*/

  }

}
