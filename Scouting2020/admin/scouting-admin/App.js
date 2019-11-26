import React from "react";
import {createSwitchNavigator, createAppContainer} from 'react-navigation';
import AuthRouter from './screens/auth/AuthRouter';


const AppRouter = createSwitchNavigator(
  {
    Auth: { screen: AuthRouter },
  },
  {
    initialRouteName: 'Auth',
  }
);

const AppContainer = createAppContainer(AppRouter);

// Now AppContainer is the main component for React to render
export default AppContainer;