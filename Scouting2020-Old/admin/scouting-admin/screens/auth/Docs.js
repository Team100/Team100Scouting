import React from 'react';
import {Component} from 'react';

import { Page } from "../../components/global/Page";
import { Menu } from "../../components/global/Menu";
import { InnerPage } from "../../components/global/InnerPage";
import { OuterPage } from "../../components/global/OuterPage";
import {Header} from "../../components/global/Header";
import {WebView} from "react-native-webview";


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
          <WebView source={{uri: 'https://scouting.docs.bz'}} />
        </InnerPage>
        <Menu />

      </OuterPage>

    </Page>
        );
    }
}