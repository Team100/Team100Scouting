import { createSwitchNavigator } from "react-navigation";
import { SignIn } from "./SignIn";
import { Docs } from "./Docs";

const AppRouter = createSwitchNavigator(
    {
      SignIn: { screen: SignIn },
      Docs: {screen: Docs}
    },
    {
      initialRouteName: 'SignIn',
    }
  );
export default AppRouter;