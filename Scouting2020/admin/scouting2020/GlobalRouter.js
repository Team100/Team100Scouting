import React from 'react';
import { StyleSheet, Text, View } from 'react-native';
import { createAppContainer, createSwitchNavigator } from 'react-navigation';

import PrimaryRouter from './screens/primary/PrimaryRouter';
const rootSwitch = createSwitchNavigator(
  {
    Primary: {screen: PrimaryRouter}
  }
)

const GlobalRouter = createAppContainer(rootSwitch);


export default GlobalRouter;
