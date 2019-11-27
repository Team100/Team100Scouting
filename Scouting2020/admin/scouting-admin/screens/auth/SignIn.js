import React from "react";
import { Component } from "react";

import { Page } from "../../components/global/Page";
import { Menu } from "../../components/global/Menu";
import { InnerPage } from "../../components/global/InnerPage";
import { OuterPage } from "../../components/global/OuterPage";
import { Header } from "../../components/global/Header";

import { Text, TextInput, Keyboard } from "react-native";
import { GhostButton } from "../../components/reusable/GhostButton";
import { DismissKeyboard } from "../../components/global/DismissKeyboard";

var globalStyles = require("../../styles/GlobalStyles");

export class SignIn extends Component {
  constructor(props) {
    super(props);
  }
  render() {
    return (
     
      <Page>
        <Header title="Sign In" />

        
          <OuterPage>
            <InnerPage>
              <TextInput
                style={globalStyles.largeTextBox}
                placeholder="User ID"
                keyboardType="number-pad"
              ></TextInput>
              <GhostButton
                style="primary"
                content="Sign In"
                callback={() => {
                  console.warn("tapped");
                }}
              />
            </InnerPage>
            <Menu />
          </OuterPage>
        
      </Page>
      
    );
  }
}
