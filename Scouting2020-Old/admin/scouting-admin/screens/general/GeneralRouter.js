import { createSwitchNavigator } from "react-navigation";

import {Home} from "./Home";
const AppRouter = createSwitchNavigator(
    {
      Home: { screen: Home },
    },
    {
      initialRouteName: 'Home',
    }
  );
export default AppRouter;