import React from 'react';
import {Component} from 'react';

import { Page } from "./components/global/Page";
import { Menu } from "./components/global/Menu";
import { InnerPage } from "./components/global/InnerPage";
import { OuterPage } from "./components/global/OuterPage";
import {Header} from "./components/global/Header";

export class SignIn extends Component{
    constructor(props){
        super (props);
    }
    render(){
        <Page>
      <Header />

      <OuterPage>
        <InnerPage>
          <Text>Open up App.js to start working on your app!</Text>
          <Text>Hello World</Text>
        </InnerPage>
        <Menu />

      </OuterPage>

    </Page>
    }
}