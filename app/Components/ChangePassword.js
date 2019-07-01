import React, { Component } from 'react';
import { Image, StyleSheet, Text, View, TextInput, KeyboardAvoidingView, Alert, AsyncStorage } from 'react-native';
import AnimateLoadingButton from 'react-native-animate-loading-button';
import DeviceInfo from 'react-native-device-info';
import './Global'

export default class ChangePassword extends Component {

    constructor(props) {
        super(props);
        this.state = {
            username:'',
            opass: '',
            npass: '',
            cpass:''
        }
    }
    componentDidMount(){
        setTimeout(this._loadInitialState);  
      }
    
    _loadInitialState = async () => {
        const  user = await AsyncStorage.getItem('user');
        this.setState({username:user})   
    }
    render() {
        return (
            <KeyboardAvoidingView style={styles.wrapper}>
                <View style={styles.container}>
                    <Text style={styles.header}>Change Password</Text>
                    <TextInput
                        style={styles.textinput} placeholder="Old Password"
                        onChangeText={(opass) => this.setState({ opass })}
                        underlineColorAndroid='#263238'
                        secureTextEntry={true}
                    />
                    <TextInput
                        style={styles.textinput} placeholder="New Password"
                        onChangeText={(npass) => this.setState({ npass })}
                        underlineColorAndroid='#263238'
                        secureTextEntry={true}
                    />
                    <TextInput
                        style={styles.textinput} placeholder="Confirm Password"
                        onChangeText={(cpass) => this.setState({ cpass })}
                        underlineColorAndroid='#263238'
                        secureTextEntry={true}
                    />
                    <AnimateLoadingButton
                        ref={c => (this.loadingButton = c)}
                        width={300}
                        height={50}
                        title="SUBMIT"
                        titleFontSize={16}
                        titleColor="rgb(255,255,255)"
                        backgroundColor="#263238"
                        borderRadius={4}
                        onPress={this._changepass.bind(this)}
                    />
                </View>
            </KeyboardAvoidingView>
        );
    }
    
    _changepass = () => {
        if(this.state.opass && this.state.npass && this.state.cpass){
            if(this.state.npass == this.state.cpass){
        var url = global.url + 'changepasswordapp.php'
        var data = new FormData()
        data.append('username',this.state.username)
        data.append('oldpass', this.state.opass)
        data.append('newpass', this.state.npass)
        data.append('cpass',this.state.cpass)
        this.loadingButton.showLoading(true);
        fetch(url, {
            method: 'POST',
            body: data  
        }).then(response => response.json())
            .then((res) => {
                this.loadingButton.showLoading(false);
                if(res.response === true){
                    Alert.alert(
                        'Successfull',
                        'Password Is Changed Successfully',
                        [
                          {text: 'OK', onPress: () => this.props.navigation.navigate('Profile')},
                        ],
                        { cancelable: false }
                      )
                }
                else{
                    const aa = JSON.stringify(res.message).slice(1,-1)
                    alert(aa)
                }
            }).catch((error) => {
               alert("Please Check Your Internet Connection");
            });
        }
        else{
            alert('New Password and Confirm Passwords Dont Match')
        }
    }
    else{
        alert('Please Fill All Details')
    }
}

}

const styles = StyleSheet.create({
    wrapper: {
        flex: 1,
    },
    container: {
        backgroundColor: '#FFFFFF',
        flex: 1,
        alignItems: 'center',
        justifyContent: 'center',
        paddingLeft: 40,
        paddingRight: 40,
    },
    header: {
        fontSize:20,
        alignSelf: 'center',
        marginBottom: 60,
    },
    textinput: {
        color: '#263238',
        alignSelf: 'stretch',
        padding: 16,
        marginBottom: 20,
    },
   
})