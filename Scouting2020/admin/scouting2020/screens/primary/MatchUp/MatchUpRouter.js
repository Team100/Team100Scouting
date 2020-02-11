import React from "react";
import { createStackNavigator } from "react-navigation-stack";
import {MatchUpList} from "./MatchUpList";



const MatchUpRouter = createStackNavigator(
    {
        MatchUpList: {screen: MatchUpList}
    },
    {
        initialRouteName: 'MatchUpList',



    }
);
export default MatchUpRouter;
