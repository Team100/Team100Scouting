import React from 'react';
import {Component} from 'react';

import { Page } from "../../components/global/Page";
import { Menu } from "../../components/global/Menu";
import { InnerPage } from "../../components/global/InnerPage";
import { OuterPage } from "../../components/global/OuterPage";
import {Header} from "../../components/global/Header";


export class Docs extends Component{
    constructor(props){
        super (props);
    }
    render(){
        return(
        <Page>
      <Header title="Docs"/>

      <OuterPage>
        <InnerPage>
        </InnerPage>
        <Menu />

      </OuterPage>

    </Page>
        );
    }
}