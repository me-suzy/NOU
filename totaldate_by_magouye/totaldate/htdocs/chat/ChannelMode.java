import java.awt.* ;

class ChannelMode extends Dialog
{
	TextField topicTextField = new TextField (40) ;
	MyList banList = new MyList (10,false) ;
	Checkbox modeI = new Checkbox ("Invite Only") ;
	Checkbox modeL = new Checkbox ("Limited to") ;
	TextField NbUsers = new TextField (4) ;
	Checkbox modeP = new Checkbox ("Private") ;
	Checkbox modeS = new Checkbox ("Secret") ;
	Checkbox modeK = new Checkbox ("Key :") ;
	TextField key = new TextField (8) ;
    Checkbox modeM = new Checkbox ("Moderated") ;
	Checkbox modeN = new Checkbox ("No external messages") ;
	Checkbox modeT = new Checkbox ("Only ops change topic") ;
	Button OK = new Button ("OK") ;
	String oldtopic,channel ;
	String lusers = "", keyValue = "";
	Connecte Serveur ;

	String OldMode, NewMode ;
	boolean ChannelOp ;

	ChannelMode ()
	{
		super (new Frame (),null,true) ;
		setLayout (new BorderLayout ()) ;

		Panel gauche = new Panel () ;
		GridBagLayout gbl = new GridBagLayout () ;
		GridBagConstraints gbc = new GridBagConstraints () ;
		gauche.setLayout (gbl) ;
		gbc.fill = GridBagConstraints.HORIZONTAL ;
		gbc.gridwidth = GridBagConstraints.REMAINDER ;
		gbl.setConstraints (modeI,gbc) ;
		gauche.add (modeI) ;
		gbc.gridwidth = 1 ;
		gbl.setConstraints (modeL,gbc) ; gauche.add (modeL) ;
		gbl.setConstraints (NbUsers,gbc) ; gauche.add (NbUsers) ;
		Label users = new Label (" users") ;
		gbc.gridwidth = GridBagConstraints.REMAINDER ;
		gbl.setConstraints (users,gbc) ; gauche.add (users) ;
		gbl.setConstraints (modeP,gbc) ; gauche.add (modeP) ;
		gbl.setConstraints (modeS,gbc) ; gauche.add (modeS) ;
		gbc.gridwidth = 1 ;
		gbl.setConstraints (modeK,gbc) ; gauche.add (modeK) ;
		gbc.gridwidth = GridBagConstraints.REMAINDER ;
		gbl.setConstraints (key,gbc) ; gauche.add (key) ;
		gbl.setConstraints (modeM,gbc) ; gauche.add (modeM) ;
		gbl.setConstraints (modeN,gbc) ; gauche.add (modeN) ;
		gbl.setConstraints (modeT,gbc) ; gauche.add (modeT) ;

		Panel bas = new Panel () ;
		bas.add (OK) ;
		bas.add (new Button ("Cancel")) ;

		add ("North",topicTextField) ;
		add ("Center",banList) ;
		add ("South",bas) ;
		add ("West",gauche) ;

		resize (300,300) ;
	}

	void setServeur (Connecte Serveur)
	{
		this.Serveur = Serveur ;
	}

	void setTopic (String topic)
	{
		topicTextField.setText (topic) ;
		oldtopic = topic ;
	}

	void setTitleChannelMode (String channel_name)
	{
		setTitle (channel_name) ;
		channel = channel_name ;
	}
	void Hide ()
	{
		banList.clear () ; // On reinitialise la ban liste
		topicTextField.enable () ;
		modeP.setState (false) ; modeP.enable () ;
		modeS.setState (false) ; modeS.enable () ;
		modeI.setState (false) ; modeI.enable () ;
		modeT.setState (false) ; modeT.enable () ;
		modeN.setState (false) ; modeN.enable () ;
		modeM.setState (false) ; modeM.enable () ;
		modeL.setState (false) ; modeL.enable () ;
		NbUsers.setText ("") ; NbUsers.enable () ; lusers = "" ;
		modeK.setState (false) ; modeK.enable () ;
		key.setText ("") ; key.enable () ; keyValue = "" ;
		show (false) ;
	}

//
// On positionne le mode du channel
// format du mode : psitnmlk lvalue kvalue
//
	void setOldMode (String mode)
	{
		int i = mode.indexOf (' ') ;
		if (i < 0) // pas de mode l ni k
			OldMode = mode ;
		else // il y a un mode l ou k ou les 2
		{
			OldMode = mode.substring (0,i) ;
			mode = mode.substring (i+1).trim () ;
			if (OldMode.indexOf ('l') >= 0)  // mode l
			{
				if ( (i = mode.indexOf (' ')) > 0) // mode k en plus
				{
					keyValue = mode.substring (i+1).trim () ;
					lusers = mode.substring (0,i) ;
				}
				else lusers = mode ;
			}
			else // uniquement un mode k
			{
				if (OldMode.indexOf ('k') >= 0) 
				{
 				   keyValue = mode ;
				}
			}
		  }
	}

	void setChannelOp (boolean op)
	{
		ChannelOp = op ;
	}

	void CheckMode ()
	{
		if (OldMode.indexOf ('p') >= 0) modeP.setState (true) ;
		if (OldMode.indexOf ('s') >= 0) modeS.setState (true) ;
		if (OldMode.indexOf ('i') >= 0) modeI.setState (true) ;
		if (OldMode.indexOf ('t') >= 0) modeT.setState (true) ;
		if (OldMode.indexOf ('n') >= 0) modeN.setState (true) ;
		if (OldMode.indexOf ('m') >= 0) modeM.setState (true) ;
		if (lusers != "")
		{
			modeL.setState (true) ;
			NbUsers.setText (lusers) ;
		}
		if (keyValue != "")
		{
			modeK.setState (true) ;
			key.setText (keyValue) ;
		}

		if (ChannelOp == false)
		{
			if (modeT.getState () == true) topicTextField.disable () ;
			modeP.disable () ;
			modeS.disable () ;
			modeI.disable () ;
			modeT.disable () ;
			modeN.disable () ;
			modeM.disable () ;
			modeL.disable () ; NbUsers.disable () ;
			modeK.disable () ; key.disable () ;
		}
	}


//
// On verifie si le mode du channel passe en 1er argument a change
//

	void CalculateNewMode (char mode, Checkbox modeCheckBox)
	{
		if (OldMode.indexOf (mode) >= 0)
		{
			if (modeCheckBox.getState () == false) 
			{
				NewMode += "-" + String.valueOf (mode) ;
			}
		}
		else
		{
			if (modeCheckBox.getState () == true) 
			{
				NewMode += "+" + String.valueOf (mode) ;
			}
		}
	}
//
// On envoie une chaine, on renvoie la chaine suivant un espace ou
// la chaine originale s'il n'y a pas d'espace
//

	String ExtractValue (String chaine)
	{
		chaine = chaine.trim () ;
		int i = chaine.indexOf (' ') ;
		if (i < 0) return chaine ;
		else return chaine.substring (i+1) ;
	}

	public boolean handleEvent (Event evt)
	{
		if (evt.id == Event.WINDOW_DESTROY)
		{
			Hide () ;
			return true ;
		}
		super.handleEvent (evt) ;
		return false ;
	}

	public boolean action (Event evt, Object arg)
	{
		if (evt.target instanceof TextField ||
		    ((evt.target instanceof Button) && arg.equals ("OK")))
			{
				// faire les changements de mode
				if (!oldtopic.equals (topicTextField.getText ()))
				{
                   Serveur.Envoie ("TOPIC " + channel + " :" + topicTextField.getText () + "\n") ;
				}
				if (ChannelOp)
				{
				  NewMode = "" ;
				  CalculateNewMode ('p',modeP) ;
				  CalculateNewMode ('s',modeS) ;
				  CalculateNewMode ('i',modeI) ;
				  CalculateNewMode ('t',modeT) ;
				  CalculateNewMode ('n',modeN) ;
				  CalculateNewMode ('m',modeM) ;
				  CalculateNewMode ('l',modeL) ;
				  CalculateNewMode ('k',modeK) ;
				  if (NewMode.indexOf ('l') >= 0) NewMode += " " + NbUsers.getText () ;
				  if (NewMode.indexOf ('k') >= 0) NewMode += " " + key.getText () ;
				  if (NewMode != "") Serveur.Envoie ("MODE " + channel+ NewMode + "\n") ;
				}
		}
		if (evt.target instanceof Button || evt.target instanceof TextField)
		{
		    Hide () ;
			return true ;
		}
		return false ;
	}
}