import React, {Component} from 'react';
import { createStackNavigator , createSwitchNavigator , createAppContainer} from 'react-navigation';
import { createMaterialBottomTabNavigator } from 'react-navigation-material-bottom-tabs';
import Icon from 'react-native-vector-icons/FontAwesome5'
import Login from './Components/Login';
import Profile from './Components/Profile';
import SplashScreen from './Components/SplashScreen';
import Details from './Components/Details';
import Scan from './Components/Scan';
import Help from './Components/Help';
import ChangePassword from './Components/ChangePassword';

const AppStack = createMaterialBottomTabNavigator({
  Profile: { screen: Profile,
    navigationOptions: {
      tabBarIcon: ({ tintColor }) => (
        <Icon size={20} name={ 'user' } style={{ color: tintColor }} />
      )
    }},
  Help: { screen: Help,
    navigationOptions: {
      tabBarIcon: ({ tintColor }) => (
        <Icon size={20} name={ 'hands-helping' } style={{ color: tintColor }} />
      )
    } },
  Password: { screen: ChangePassword,
    navigationOptions: {
      tabBarIcon: ({ tintColor }) => (
        <Icon size={20} name={ 'key' } style={{ color: tintColor }} />
      )
    } },
  Details: { screen: Details,
    navigationOptions: {
      tabBarIcon: ({ tintColor }) => (
        <Icon size={20} name={ 'chart-area' } style={{ color: tintColor }} />
      )
    } },
}, {
  initialRouteName: 'Profile',
  activeColor: '#f0edf6',
  inactiveColor: '#4f5b62',
  barStyle: { backgroundColor: '#263238' },
});

const AuthStack = createStackNavigator({
  Login: { 
    screen: Login,
    navigationOptions: () => ({
      title: `LOGIN`,
      headerLeft: null,
      headerTitleStyle: { 
        textAlign:"center", 
        flex:1 
    },
    }),
  },
});


const switchy = createSwitchNavigator({
  AuthLoading : SplashScreen,
  Auth : AuthStack,
  App : AppStack,
  Scan : Scan 
},
{
  initialRouteName: 'AuthLoading'
})

const App = createAppContainer(switchy);

export default App;