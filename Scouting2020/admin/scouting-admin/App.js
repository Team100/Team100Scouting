import React from "react";
import { StyleSheet, Text, View } from "react-native";
import { PageLayout } from "./shared/PageLayout";
import { Page } from "./components/global/Page";
import { Menu } from "./components/global/Menu";
import { InnerPage } from "./components/global/InnerPage";
import { OuterPage } from "./components/global/OuterPage";
import {Header} from "./components/global/Header";

export default function App() {
  console.log(PageLayout.isTabletLayout());
  console.log(PageLayout.getDeviceWidth());
  console.log(PageLayout.getDeviceHeight());
  return (
    <Page>
      <Header title = "App.js"/>

      <OuterPage>
        <InnerPage>
          <Text>Open up App.js to start working on your app!</Text>
          <Text>Hello World</Text>
        </InnerPage>
        <Menu />

      </OuterPage>

    </Page>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: "#fff",
    alignItems: "center",
    justifyContent: "center"
  }
});
