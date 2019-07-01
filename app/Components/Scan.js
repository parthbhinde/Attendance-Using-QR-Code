import React, { Component } from 'react'
import {Alert, Text, BackHandler  , StyleSheet, AsyncStorage} from 'react-native'
import QRCodeScanner from 'react-native-qrcode-scanner'
import publicIP from 'react-native-public-ip';
import './Global'

export default class Scan extends Component {
  constructor(props) {
    super(props);
    this.handleBackButtonClick = this.handleBackButtonClick.bind(this);
    this.state = {
      myPass: null,
      myUser: null,
      myUid : null,
      myIp : null,
      att: false
    }
}
componentDidMount(){
  publicIP().then(ip => {this.setState({myIp : ip})}).catch(error => {console.log(error);})
}
componentWillMount() {
  BackHandler.addEventListener('hardwareBackPress', this.handleBackButtonClick);
}

componentWillUnmount() {
  BackHandler.removeEventListener('hardwareBackPress', this.handleBackButtonClick);
}

handleBackButtonClick() {
  this.props.navigation.navigate('Profile');
  return true;
}
  
async onSuccess(e) {
  const  user = await AsyncStorage.getItem('user');
  this.setState({myUser: user});
  const  pass = await AsyncStorage.getItem('pass');
  this.setState({myPass: pass});
  const uid = await AsyncStorage.getItem('uid');
  this.setState({myUid : uid});
  var data = new FormData()
  data.append('username', this.state.myUser)
  data.append('password', this.state.myPass)
  data.append('uid' , this.state.myUid)
  data.append('ipad' , this.state.myIp)
  AsyncStorage.setItem('ip', this.state.myIp)
  fetch(e.data, {
    method: 'POST',
    body: data  
}).then(response => response.json())
    .then((res) => {
      if(res.status === 'success'){
        Alert.alert(
          'Successfull',
          'Attendance Was Marked Successfully',
          [
            {text: 'OK', onPress: () => this.props.navigation.navigate('Profile')},
          ],
          { cancelable: false }
        )
      }
      else if(res.status === 'error'){
        Alert.alert(
          'Error',
          'Something Went Wrong',
          [
            {text: 'Try Again', onPress: () => this.scanner.reactivate()},
            {text: 'OK', onPress: () => this.props.navigation.navigate('Profile')},
          ],
          { cancelable: false }
        )
      }
      else if(res.status === 'blocked'){
        Alert.alert(
          'Blocked',
          'Your Account is Blocked by Faculty',
          [
            {text: 'OK', onPress: () => this.props.navigation.navigate('Profile')},
          ],
          { cancelable: false }
        )
      }
      else if(res.status === 'creds'){
        Alert.alert(
          'Wrong Class',
          'There are Problems With Your Credentials',
          [
            {text: 'Try Again', onPress: () => this.scanner.reactivate()},
            {text: 'OK', onPress: () => this.props.navigation.navigate('Profile')},
          ],
          { cancelable: false }
        )
      }
      else if(res.status === 'over'){
        Alert.alert(
          'Over',
          'The Attendance Taking Process is Finished',
          [
            {text: 'OK', onPress: () => this.props.navigation.navigate('Profile')},
          ],
          { cancelable: false }
        )
      }
      else{
        Alert.alert(
          'Something Went Wrong',
          'Try Again After Sometime',
          [
            {text: 'OK', onPress: () => this.props.navigation.navigate('Profile')},
          ],
          { cancelable: false }
        )
      }
    }) 
}
render() {
  return (
    <QRCodeScanner
    ref={(node) => { this.scanner = node }}
      onRead={this.onSuccess.bind(this)}
      topContent={
        <Text style={styles.centerText}>
          <Text style={styles.textBold}>Please try and center the displayed QR Code</Text>
        </Text>
      }
      
    />
  );
}
}
 
const styles = StyleSheet.create({
  centerText: {
    flex: 1,
    fontSize: 18,
    padding: 32,
    color: '#777',
  },
  textBold: {
    fontWeight: '500',
    color: '#000',
  },
  buttonText: {
    fontSize: 21,
    color: 'rgb(0,122,255)',
  },
  buttonTouchable: {
    padding: 16,
  },
});