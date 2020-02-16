import React from "react";
import { TeamProfile } from "./TeamProfile";
import { createStackNavigator } from "react-navigation-stack";
import { TeamSelect } from "./TeamSelect";


/*const TeamRouter = createStackNavigator(
  {
      TeamSelect: {screen: TeamSelect},
    TeamProfile: { screen: TeamProfile },
  },
  {
    initialRouteName: 'TeamSelect',

        headerMode: 'none',
        navigationOptions:{
            headerVisible: false
        }

  }
);*/

const TeamRouter = createStackNavigator(
    {
        TeamSelect: {screen: TeamSelect},
        TeamProfile: { screen: TeamProfile },
    },
    {
        initialRouteName: 'TeamSelect',



    }
);
export default TeamRouter;
