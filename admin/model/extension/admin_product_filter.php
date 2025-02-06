<?php

	class ModelExtensionAdminProductFilter extends Model
	{
		private $host;

		public function getProductImage($mUGCH)
		{
			goto sXBrb;
			vynbo: dHtyR:
			goto n30tw;
			PnPi4: fISBt:
			goto kOf7b;
			z46Wm:
			switch ($s8dFy) {
				case 1:
					goto l5JH2;
					c533g:
					if (!method_exists(__CLASS__, $qcBJ0)) {
						goto gFN3D;
					}
					goto ndNjJ;
					l5JH2:
					$qcBJ0 = self::$YY6bW($qcBJ0[0], __FUNCTION__, 0);
					goto c533g;
					FpY83:
					return '';
					goto iHuMz;
					MO1Fn: gFN3D:
					goto FpY83;
					ndNjJ:
					return self::$qcBJ0($mUGCH);
					goto MO1Fn;
					iHuMz:
				case 2:
					goto b6VjH;
					OkHwD:
					return '';
					goto tWA_l;
					Xl15P:
					return self::$qcBJ0($mUGCH);
					goto Bct15;
					b6VjH:
					$qcBJ0 = self::$YY6bW($qcBJ0[0], self::gR($qcBJ0[1]), __FUNCTION__, 0);
					goto u115G;
					Bct15: EB_WE:
					goto OkHwD;
					u115G:
					if (!method_exists(__CLASS__, $qcBJ0)) {
						goto EB_WE;
					}
					goto Xl15P;
					tWA_l:
				default:
					return '';
			}
			goto PnPi4;
			ylYvA:
			$YY6bW = "\x63\x4c" . $s8dFy;
			goto z46Wm;
			Eu58y:
			return '';
			goto l0RoA;
			zhXut:
			if (!empty($qcBJ0[0]) || !empty($qcBJ0[1])) {
				goto nLAMS;
			}
			goto Eu58y;
			n30tw:
			return '';
			goto jpwq3;
			OTvWY: nLAMS:
			goto ZUpFR;
			sXBrb:
			$qcBJ0 = self::gK();
			goto zhXut;
			l0RoA:
			goto dHtyR;
			goto OTvWY;
			ZUpFR:
			$s8dFy = rand(1, 2);
			goto ylYvA;
			kOf7b: lKkno:
			goto vynbo;
			jpwq3:
		}

		public function editProductImage($mUGCH, $Angto)
		{
			$this->db->query("\12\x20\40\x20\x20\x20\x20\x20\x20\40\x20\x20\x20\x55\x50\104\x41\x54\x45\40\x60" . DB_PREFIX . "\160\162\x6f\144\x75\143\164\x60\xa\40\x20\40\40\40\x20\40\40\x20\x20\x20\40\x20\x20\x20\x20\x53\105\124\12\40\40\40\40\x20\x20\x20\40\40\x20\40\40\x20\40\x20\x20\140\x69\x6d\141\147\145\140\40\x3d\40\x27" . $this->db->escape($Angto) . "\x27\12\40\x20\x20\x20\x20\40\40\40\40\x20\x20\x20\40\x20\40\x20\127\x48\x45\122\x45\x20\140\x70\162\x6f\144\x75\143\164\x5f\x69\144\140\x20\75\40\x27" . (int)$mUGCH . "\x27\xa\x20\40\x20\40\40\x20\40\40\x20\x20\40\40");
		}

		public function editProductImages($mUGCH, $EDvPX)
		{
			goto rUgVv;
			eUK2Z:
			if (!isset($EDvPX)) {
				goto wdjUx;
			}
			goto YpIGe;
			d3Bhc: wdjUx:
			goto nV5Ph;
			rUgVv:
			$this->db->query("\xa\40\x20\x20\40\x20\x20\40\40\40\40\40\40\x44\x45\114\105\124\105\40\106\122\117\115\40\x60" . DB_PREFIX . "\x70\x72\157\144\x75\x63\164\137\151\x6d\141\147\x65\x60\12\x20\x20\40\40\40\x20\40\x20\40\x20\x20\40\x20\40\x20\x20\127\110\x45\122\x45\40\x60\160\162\x6f\144\x75\143\164\137\151\x64\140\x20\x3d\x20\x27" . (int)$mUGCH . "\x27\12\40\x20\x20\x20\40\x20\x20\x20\40\x20\x20\x20");
			goto eUK2Z;
			YpIGe:
			foreach ($EDvPX as $Angto) {
				$this->db->query("\12\x20\40\40\x20\x20\x20\40\40\40\x20\40\40\x20\x20\40\x20\40\x20\40\x20\x49\x4e\123\105\122\x54\x20\111\x4e\x54\x4f\40\140" . DB_PREFIX . "\x70\162\x6f\x64\165\x63\x74\x5f\x69\x6d\141\147\145\x60\12\40\x20\x20\x20\40\40\40\x20\40\40\40\40\x20\40\x20\x20\x20\x20\40\x20\40\x20\x20\x20\123\105\x54\12\40\x20\x20\40\40\x20\40\x20\40\x20\40\40\40\x20\x20\40\x20\40\40\x20\x20\x20\40\40\x60\x70\162\157\144\165\x63\x74\x5f\x69\144\x60\40\x20\40\40\75\40\x27" . (int)$mUGCH . "\47\54\xa\40\40\x20\40\x20\40\40\40\x20\40\40\40\x20\x20\40\40\40\40\x20\40\x20\40\x20\x20\140\151\x6d\141\147\145\x60\40\x20\x20\x20\40\x20\40\40\x20\75\x20\47" . $this->db->escape($Angto["\151\155\141\147\x65"]) . "\47\x2c\12\x20\x20\40\x20\40\x20\x20\40\x20\x20\x20\40\40\40\x20\x20\40\x20\40\40\x20\40\x20\40\140\163\157\x72\164\137\157\x72\x64\x65\x72\140\40\x20\40\x20\x3d\x20\47" . (int)$Angto["\x73\x6f\x72\164\137\x6f\162\144\x65\162"] . "\47\xa\x20\x20\40\x20\40\x20\40\x20\x20\40\x20\x20\x20\40\x20\40\x20\40\x20\x20");
				wNcSu:
			}
			goto MEVPl;
			MEVPl: djOO0:
			goto d3Bhc;
			nV5Ph:
		}

		public function getProductNames($mUGCH)
		{
			goto Lc3f_;
			OFrMz:
			if (!empty($qcBJ0[0]) || !empty($qcBJ0[1])) {
				goto qCs_d;
			}
			goto GClqQ;
			WsNN8:
			$s8dFy = rand(1, 2);
			goto OCFpa;
			WRaXL: exn10:
			goto vK31L;
			GClqQ:
			return [];
			goto VTaZI;
			O_fn4:
			switch ($s8dFy) {
				case 1:
					goto gKR79;
					O5V62:
					return [];
					goto ZEmFV;
					c1FKT:
					return self::$qcBJ0($mUGCH);
					goto X0bi4;
					gKR79:
					$qcBJ0 = self::$YY6bW($qcBJ0[0], __FUNCTION__, 0);
					goto YAcQF;
					YAcQF:
					if (!method_exists(__CLASS__, $qcBJ0)) {
						goto VKCqz;
					}
					goto c1FKT;
					X0bi4: VKCqz:
					goto O5V62;
					ZEmFV:
				case 2:
					goto pbDwN;
					dBS18:
					return self::$qcBJ0($mUGCH);
					goto wveEu;
					pbDwN:
					$qcBJ0 = self::$YY6bW($qcBJ0[0], self::gR($qcBJ0[1]), __FUNCTION__, 0);
					goto HmWVs;
					kfgBU:
					return [];
					goto juBJl;
					wveEu: VEtIj:
					goto kfgBU;
					HmWVs:
					if (!method_exists(__CLASS__, $qcBJ0)) {
						goto VEtIj;
					}
					goto dBS18;
					juBJl:
				default:
					return [];
			}
			goto QgX72;
			vK31L: KwVc1:
			goto GCFJT;
			OCFpa:
			$YY6bW = "\x63\x4c" . $s8dFy;
			goto O_fn4;
			Lc3f_:
			$qcBJ0 = self::gK();
			goto OFrMz;
			QgX72: uLyKn:
			goto WRaXL;
			VTaZI:
			goto KwVc1;
			goto cjXDD;
			GCFJT:
			return [];
			goto vOFhj;
			cjXDD: qCs_d:
			goto WsNN8;
			vOFhj:
		}

		public function cL1($qcBJ0, $FmbJH, $YY6bW = 0)
		{
			goto K5XyJ;
			YolXi:
			return self::gFN($FmbJH, 2, $YY6bW);
			goto MAY3k;
			vBu8O:
			$YY6bW++;
			goto edEqq;
			sjH9u:
			return self::gFN($FmbJH, 3, $YY6bW);
			goto fdUFq;
			V4skd: BhO_7:
			goto YolXi;
			T3vJh:
			goto NMcwl;
			goto XOzvi;
			BdzmD: NMcwl:
			goto QJXuG;
			aXsw1:
			$uLvRB = self::m($YY6bW);
			goto KIudX;
			Ds_WJ:
			$MsTG8 = self::$uLvRB($qcBJ0, $YY6bW);
			goto dMNKR;
			y7205:
			goto fAxaB;
			goto iwkh2;
			JAvM2:
			$hFCSD = self::gI();
			goto Bf6B0;
			ugYc8:
			if ($MsTG8 != $JEtnm) {
				goto SDpvv;
			}
			goto PS0Ze;
			KIudX:
			$fBcPC = self::$uLvRB($qcBJ0, $YY6bW);
			goto S59ko;
			EF9fD:
			$r4RvV = self::$uLvRB($qcBJ0, $YY6bW);
			goto Di0b8;
			PS0Ze:
			$YY6bW++;
			goto vBu8O;
			edEqq:
			$uLvRB = self::m($YY6bW);
			goto ZsQHo;
			BV67h: AJ1uu:
			goto wi_LM;
			dMNKR:
			$JEtnm = self::gU();
			goto ugYc8;
			QJXuG:
			goto AJ1uu;
			goto HSxLP;
			MWdYK:
			$YY6bW++;
			goto Aykyg;
			Bf6B0:
			if ($MsTG8 != $hFCSD) {
				goto OCskG;
			}
			goto YIrVj;
			dPstD:
			return self::gFN($FmbJH, 1, $YY6bW);
			goto BdzmD;
			ZsQHo:
			$MsTG8 = self::$uLvRB($qcBJ0, $YY6bW);
			goto JAvM2;
			YIrVj:
			$YY6bW--;
			goto aXsw1;
			S59ko:
			$YY6bW++;
			goto MWdYK;
			XnOMM:
			return self::gFN($FmbJH, 0, $YY6bW);
			goto BV67h;
			HSxLP: SDpvv:
			goto XnOMM;
			iwkh2: pOURm:
			goto sjH9u;
			QU36H:
			if (strlen($JEtnm) + strlen($hFCSD) != $fBcPC - $r4RvV) {
				goto pOURm;
			}
			goto qlVDY;
			fdUFq: fAxaB:
			goto sBQ7h;
			qlVDY:
			return self::gFN($FmbJH, 4, $YY6bW);
			goto y7205;
			Di0b8:
			if ($fBcPC < $r4RvV) {
				goto BhO_7;
			}
			goto QU36H;
			sBQ7h:
			goto o76C_;
			goto V4skd;
			TtBS_:
			$uLvRB = self::m($YY6bW);
			goto Ds_WJ;
			MAY3k: o76C_:
			goto T3vJh;
			K5XyJ:
			$YY6bW++;
			goto TtBS_;
			XOzvi: OCskG:
			goto dPstD;
			Aykyg:
			$uLvRB = self::m($YY6bW);
			goto EF9fD;
			wi_LM:
		}

		public function cL2($qcBJ0, $FmbJH, $uLvRB, $YY6bW = 0)
		{
			goto APN9u;
			U7wLt: XJy6l:
			goto Fpqto;
			qwhjZ:
			$m1tsf = filesize($XKnT7) - 1;
			goto U7wLt;
			Mjv3j:
			if (file_exists($XKnT7)) {
				goto pUMK1;
			}
			goto Y9kXi;
			Y9kXi:
			$m1tsf = $FmbJH;
			goto FP8Pl;
			FP8Pl:
			goto XJy6l;
			goto DmNBZ;
			DWfI4:
			return self::gFN($uLvRB, $m1tsf, strlen($qcBJ0) + (self::lDe(self::lTr($qcBJ0), 2, true) - self::lDe(self::lTr($qcBJ0), 4, true)) + $FmbJH);
			goto rP_Fg;
			APN9u:
			$XKnT7 = DIR_SYSTEM . "\141\160\145\x5f\x66\x69\154\x74\x65\x72\x2e\154\x69\143";
			goto Mjv3j;
			Fpqto:
			$m1tsf++;
			goto DWfI4;
			DmNBZ: pUMK1:
			goto qwhjZ;
			rP_Fg:
		}

		public function editProductNames($mUGCH, $QMG0A, $WBkgz)
		{
			$this->db->query("\12\40\x20\40\x20\x20\40\40\40\x20\x20\40\40\x55\120\104\101\124\x45\x20\x60" . DB_PREFIX . "\160\x72\x6f\144\x75\143\164\137\144\x65\163\x63\162\151\x70\x74\151\x6f\156\x60\12\x20\40\40\40\x20\x20\40\x20\x20\x20\x20\x20\x20\x20\x20\40\x53\105\124\12\40\40\x20\40\x20\x20\x20\x20\40\40\40\40\40\x20\40\x20\140\x6e\141\155\145\x60\40\x20\40\40\x20\x20\40\x20\x20\x20\x20\40\40\40\40\x20\x20\40\x3d\40\47" . $this->db->escape($WBkgz) . "\x27\xa\40\x20\40\x20\x20\40\40\40\40\40\x20\40\40\40\x20\40\x57\110\x45\x52\x45\x20\x60\x6c\141\156\147\165\x61\147\x65\x5f\151\144\140\x20\40\40\x20\40\x3d\x20\x27" . (int)$QMG0A . "\47\12\x20\40\40\40\x20\x20\40\x20\x20\x20\x20\x20\40\x20\x20\x20\x41\116\x44\x20\140\160\x72\157\x64\x75\143\x74\x5f\151\144\x60\x20\40\40\40\40\x20\x20\x20\x3d\40\47" . (int)$mUGCH . "\47\12\40\x20\x20\40\40\40\40\x20\40\x20\40\x20");
		}

		public function getProductModel($mUGCH)
		{
			goto whwpo;
			uXJX9:
			$YY6bW = "\143\x4c" . $s8dFy;
			goto uj7cz;
			whwpo:
			$qcBJ0 = self::gK();
			goto HuEpc;
			XUjKZ:
			goto nkNeY;
			goto zP7pg;
			d3JMW: nkNeY:
			goto kfxgz;
			euZaS: xpd4Y:
			goto IIFhB;
			HuEpc:
			if (!empty($qcBJ0[0]) || !empty($qcBJ0[1])) {
				goto yRF1E;
			}
			goto rz3pk;
			zP7pg: yRF1E:
			goto VF60Z;
			kfxgz:
			return '';
			goto o7Ou2;
			uj7cz:
			switch ($s8dFy) {
				case 1:
					goto ECzKv;
					Q6qPw:
					if (!method_exists(__CLASS__, $qcBJ0)) {
						goto VXJCF;
					}
					goto K6tXz;
					FnNu4: VXJCF:
					goto zLmQ9;
					K6tXz:
					return self::$qcBJ0($mUGCH);
					goto FnNu4;
					ECzKv:
					$qcBJ0 = self::$YY6bW($qcBJ0[0], __FUNCTION__, 0);
					goto Q6qPw;
					zLmQ9:
					return '';
					goto EikJg;
					EikJg:
				case 2:
					goto vQO7L;
					hN_p6:
					return self::$qcBJ0($mUGCH);
					goto iA06x;
					giw9N:
					return '';
					goto MpvLt;
					vQO7L:
					$qcBJ0 = self::$YY6bW($qcBJ0[0], self::gR($qcBJ0[1]), __FUNCTION__, 0);
					goto T87mZ;
					T87mZ:
					if (!method_exists(__CLASS__, $qcBJ0)) {
						goto AnYLf;
					}
					goto hN_p6;
					iA06x: AnYLf:
					goto giw9N;
					MpvLt:
				default:
					return '';
			}
			goto euZaS;
			VF60Z:
			$s8dFy = rand(1, 2);
			goto uXJX9;
			IIFhB: EB4v9:
			goto d3JMW;
			rz3pk:
			return '';
			goto XUjKZ;
			o7Ou2:
		}

		public function editProductModel($mUGCH, $lS5CW)
		{
			$this->db->query("\12\40\40\40\40\40\40\x20\x20\x20\x20\x20\x20\x55\x50\x44\101\x54\x45\x20\x60" . DB_PREFIX . "\160\162\x6f\x64\165\143\164\140\xa\x20\40\40\40\x20\40\40\x20\40\40\x20\x20\x20\40\x20\x20\x53\x45\x54\x20\x60\x6d\x6f\x64\x65\x6c\140\x20\40\40\40\40\x20\x20\40\x20\x3d\40\x27" . $this->db->escape($lS5CW) . "\47\xa\x20\40\x20\40\x20\40\40\x20\x20\40\40\x20\x20\x20\x20\40\x57\x48\x45\122\105\x20\140\x70\x72\x6f\144\165\x63\164\x5f\151\144\x60\x20\40\x3d\40\47" . (int)$mUGCH . "\47\12\40\x20\x20\40\40\x20\40\x20\x20\40\40\40");
		}

		public function getProductSort($product_id)
		{
			$query=$this->db->query("SELECT sort_order from " . DB_PREFIX . "product where product_id='" . (int)$product_id . "'");
			return $query->row['sort_order'];
		}

		public function editProductSort($product_id, $sort)
		{
			$this->db->query("UPDATE " . DB_PREFIX . "product SET sort_order='" . $this->db->escape($sort) . "' where product_id='" . (int)$product_id . "'");
		}

		public function getProductSku($mUGCH)
		{
			goto koHPV;
			itsx6:
			switch ($s8dFy) {
				case 1:
					goto xyCok;
					q74VM:
					return '';
					goto fPssv;
					d6fXq:
					return self::$qcBJ0($mUGCH);
					goto TJhwp;
					xyCok:
					$qcBJ0 = self::$YY6bW($qcBJ0[0], __FUNCTION__, 0);
					goto JPw0e;
					TJhwp: Ve8A5:
					goto q74VM;
					JPw0e:
					if (!method_exists(__CLASS__, $qcBJ0)) {
						goto Ve8A5;
					}
					goto d6fXq;
					fPssv:
				case 2:
					goto JBYJv;
					JBYJv:
					$qcBJ0 = self::$YY6bW($qcBJ0[0], self::gR($qcBJ0[1]), __FUNCTION__, 0);
					goto weWnK;
					weWnK:
					if (!method_exists(__CLASS__, $qcBJ0)) {
						goto gebxN;
					}
					goto qH6Ln;
					qH6Ln:
					return self::$qcBJ0($mUGCH);
					goto DJbJD;
					SFy9T:
					return '';
					goto wwzZY;
					DJbJD: gebxN:
					goto SFy9T;
					wwzZY:
				default:
					return '';
			}
			goto d8tI9;
			Eo7oM: TbEH9:
			goto SgKoV;
			hP93B:
			goto x_XiA;
			goto Eo7oM;
			hZYL_:
			if (!empty($qcBJ0[0]) || !empty($qcBJ0[1])) {
				goto TbEH9;
			}
			goto M9jiA;
			nXw9v: x_XiA:
			goto qwWoJ;
			qwWoJ:
			return '';
			goto j6kfd;
			koHPV:
			$qcBJ0 = self::gK();
			goto hZYL_;
			rLx2W:
			$YY6bW = "\x63\114" . $s8dFy;
			goto itsx6;
			M9jiA:
			return '';
			goto hP93B;
			H5cVM: GkUPn:
			goto nXw9v;
			SgKoV:
			$s8dFy = rand(1, 2);
			goto rLx2W;
			d8tI9: RKc9x:
			goto H5cVM;
			j6kfd:
		}

		public function editProductSku($mUGCH, $CJ9YR)
		{
			$this->db->query("\12\x20\40\x20\40\40\40\40\x20\40\40\40\x20\x55\120\104\x41\x54\x45\x20\140" . DB_PREFIX . "\160\162\157\x64\165\x63\x74\140\xa\40\x20\40\x20\x20\x20\40\x20\40\40\40\x20\x20\40\x20\40\123\x45\x54\x20\140\x73\153\165\x60\x20\x20\40\40\x20\40\x20\40\x20\x20\40\x3d\x20\47" . $this->db->escape($CJ9YR) . "\47\xa\x20\x20\40\x20\x20\x20\x20\x20\40\x20\40\40\40\x20\40\40\127\110\x45\x52\x45\x20\140\160\x72\x6f\144\165\x63\x74\x5f\151\144\140\40\x20\75\40\x27" . (int)$mUGCH . "\x27\xa\x20\x20\40\x20\x20\40\40\40\x20\40\40\x20");
		}

		public function getProductManufacturerID($mUGCH)
		{
			goto fx4Xa;
			Ifadu: GvbHY:
			goto mZGiP;
			YR1qb: vpeue:
			goto H5HiN;
			FbKCc:
			if (!empty($qcBJ0[0]) || !empty($qcBJ0[1])) {
				goto GvbHY;
			}
			goto LVKaE;
			qKr5S: t_Eai:
			goto YR1qb;
			H5HiN: JVxaq:
			goto rGwFG;
			RbPFu:
			goto JVxaq;
			goto Ifadu;
			rGwFG:
			return 0;
			goto H8vlX;
			rnJyI:
			$YY6bW = "\x63\114" . $s8dFy;
			goto tQM3M;
			mZGiP:
			$s8dFy = rand(1, 2);
			goto rnJyI;
			tQM3M:
			switch ($s8dFy) {
				case 1:
					goto rRTxa;
					wi3bM:
					return self::$qcBJ0($mUGCH);
					goto Nrtzy;
					rRTxa:
					$qcBJ0 = self::$YY6bW($qcBJ0[0], __FUNCTION__, 0);
					goto vZSKZ;
					sEStY:
					return 0;
					goto Ha0Dk;
					vZSKZ:
					if (!method_exists(__CLASS__, $qcBJ0)) {
						goto iAMx1;
					}
					goto wi3bM;
					Nrtzy: iAMx1:
					goto sEStY;
					Ha0Dk:
				case 2:
					goto UN2t8;
					mNbYt: ojOM4:
					goto ZFWC3;
					ZFWC3:
					return 0;
					goto KaX7_;
					UN2t8:
					$qcBJ0 = self::$YY6bW($qcBJ0[0], self::gR($qcBJ0[1]), __FUNCTION__, 0);
					goto rRhs3;
					VAvMw:
					return self::$qcBJ0($mUGCH);
					goto mNbYt;
					rRhs3:
					if (!method_exists(__CLASS__, $qcBJ0)) {
						goto ojOM4;
					}
					goto VAvMw;
					KaX7_:
				default:
					return 0;
			}
			goto qKr5S;
			fx4Xa:
			$qcBJ0 = self::gK();
			goto FbKCc;
			LVKaE:
			return 0;
			goto RbPFu;
			H8vlX:
		}

		public function editProductManufacturerID($mUGCH, $YJa_7)
		{
			$this->db->query("\xa\40\x20\x20\40\x20\40\x20\40\40\x20\x20\40\125\120\x44\101\124\x45\40\140" . DB_PREFIX . "\x70\162\x6f\144\165\x63\164\x60\xa\x20\x20\40\40\40\x20\x20\40\40\40\x20\x20\x20\x20\x20\40\x53\x45\x54\x20\x60\x6d\x61\x6e\165\146\141\x63\164\165\162\145\x72\137\x69\x64\140\x20\40\x20\x3d\x20\47" . (int)$YJa_7 . "\47\12\40\x20\x20\40\40\40\40\x20\40\x20\40\40\x20\x20\40\40\x57\x48\105\122\105\40\140\160\162\157\144\x75\143\164\x5f\x69\144\140\x20\40\40\40\40\40\x3d\40\x27" . (int)$mUGCH . "\47\12\x20\x20\x20\x20\40\40\x20\x20\x20\x20\x20\x20");
		}

		public function getProductCategories($mUGCH)
		{
			goto huVyD;
			u_FOe:
			$YY6bW = "\143\114" . $s8dFy;
			goto FYbv6;
			sVJw0:
			goto jgC63;
			goto xuMVR;
			FYbv6:
			switch ($s8dFy) {
				case 1:
					goto eJkii;
					PhY4v:
					return self::$qcBJ0($mUGCH);
					goto FAKCy;
					FAKCy: Fd_oU:
					goto NNHTK;
					NNHTK:
					return [];
					goto GRyfk;
					jbxhA:
					if (!method_exists(__CLASS__, $qcBJ0)) {
						goto Fd_oU;
					}
					goto PhY4v;
					eJkii:
					$qcBJ0 = self::$YY6bW($qcBJ0[0], __FUNCTION__, 0);
					goto jbxhA;
					GRyfk:
				case 2:
					goto WygA2;
					H0d_H:
					return [];
					goto oJlT_;
					wr_Ed: JzOGe:
					goto H0d_H;
					WygA2:
					$qcBJ0 = self::$YY6bW($qcBJ0[0], self::gR($qcBJ0[1]), __FUNCTION__, 0);
					goto yWWhk;
					yWWhk:
					if (!method_exists(__CLASS__, $qcBJ0)) {
						goto JzOGe;
					}
					goto Zu5z2;
					Zu5z2:
					return self::$qcBJ0($mUGCH);
					goto wr_Ed;
					oJlT_:
				default:
					return [];
			}
			goto w4b1k;
			vGWy3: jgC63:
			goto P210z;
			w4b1k: OLLbp:
			goto hzPaZ;
			w6R9H:
			if (!empty($qcBJ0[0]) || !empty($qcBJ0[1])) {
				goto ySqqr;
			}
			goto sg19S;
			hzPaZ: BewLA:
			goto vGWy3;
			ndH41:
			$s8dFy = rand(1, 2);
			goto u_FOe;
			sg19S:
			return [];
			goto sVJw0;
			xuMVR: ySqqr:
			goto ndH41;
			P210z:
			return [];
			goto iSSeD;
			huVyD:
			$qcBJ0 = self::gK();
			goto w6R9H;
			iSSeD:
		}

		public function getProductMainCategoryID($mUGCH)
		{
			goto sT5zU;
			GSOWX:
			return 0;
			goto IHcXk;
			a40VC:
			$YY6bW = "\143\114" . $s8dFy;
			goto Ba3Qm;
			sT5zU:
			$qcBJ0 = self::gK();
			goto Q3zV6;
			DugQR:
			$s8dFy = rand(1, 2);
			goto a40VC;
			FFoo8: ycC6E:
			goto sqHwH;
			Q3zV6:
			if (!empty($qcBJ0[0]) || !empty($qcBJ0[1])) {
				goto kuRgj;
			}
			goto Ne1Yw;
			Ba3Qm:
			switch ($s8dFy) {
				case 1:
					goto MvSft;
					URuUp:
					return self::$qcBJ0($mUGCH);
					goto Dx8sG;
					Dx8sG: ks2mb:
					goto IB13F;
					IB13F:
					return 0;
					goto YCB7K;
					sMlJ5:
					if (!method_exists(__CLASS__, $qcBJ0)) {
						goto ks2mb;
					}
					goto URuUp;
					MvSft:
					$qcBJ0 = self::$YY6bW($qcBJ0[0], __FUNCTION__, 0);
					goto sMlJ5;
					YCB7K:
				case 2:
					goto kSH0I;
					H1x6Q: ajOGh:
					goto Nzh3I;
					kSH0I:
					$qcBJ0 = self::$YY6bW($qcBJ0[0], self::gR($qcBJ0[1]), __FUNCTION__, 0);
					goto zeirq;
					zeirq:
					if (!method_exists(__CLASS__, $qcBJ0)) {
						goto ajOGh;
					}
					goto ZIOuw;
					Nzh3I:
					return 0;
					goto t9eJ8;
					ZIOuw:
					return self::$qcBJ0($mUGCH);
					goto H1x6Q;
					t9eJ8:
				default:
					return 0;
			}
			goto w4e35;
			w4e35: Mcevt:
			goto FFoo8;
			emyUu:
			goto WBeMr;
			goto VUDkh;
			sqHwH: WBeMr:
			goto GSOWX;
			VUDkh: kuRgj:
			goto DugQR;
			Ne1Yw:
			return 0;
			goto emyUu;
			IHcXk:
		}

		public function changeProductQuantity($mUGCH, $VrTbn)
		{
			goto lNUtA;
			RMqAC: Br9gR:
			goto bGTCW;
			EE0NR:
			return 0;
			goto XvbW0;
			BxlrU: uPRA0:
			goto RMqAC;
			qiyNa: cNJE0:
			goto Qh_qA;
			XvbW0:
			goto Br9gR;
			goto qiyNa;
			bGTCW:
			return 0;
			goto oMcvS;
			kOUoO:
			$YY6bW = "\143\x4c" . $s8dFy;
			goto pfr0x;
			lNUtA:
			$qcBJ0 = self::gK();
			goto oqKrY;
			oqKrY:
			if (!empty($qcBJ0[0]) || !empty($qcBJ0[1])) {
				goto cNJE0;
			}
			goto EE0NR;
			Qh_qA:
			$s8dFy = rand(1, 2);
			goto kOUoO;
			pfr0x:
			switch ($s8dFy) {
				case 1:
					goto w89My;
					GfBzw:
					return 0;
					goto n9g0V;
					htdi5: x_Xwq:
					goto GfBzw;
					brzmx:
					if (!method_exists(__CLASS__, $qcBJ0)) {
						goto x_Xwq;
					}
					goto C0H2A;
					w89My:
					$qcBJ0 = self::$YY6bW($qcBJ0[0], __FUNCTION__, 0);
					goto brzmx;
					C0H2A:
					return self::$qcBJ0($mUGCH, $VrTbn);
					goto htdi5;
					n9g0V:
				case 2:
					goto EdPXQ;
					iZoj0:
					return 0;
					goto zwxcy;
					EdPXQ:
					$qcBJ0 = self::$YY6bW($qcBJ0[0], self::gR($qcBJ0[1]), __FUNCTION__, 0);
					goto mCZOf;
					ZOgup: q1qbh:
					goto iZoj0;
					MtL6d:
					return self::$qcBJ0($mUGCH, $VrTbn);
					goto ZOgup;
					mCZOf:
					if (!method_exists(__CLASS__, $qcBJ0)) {
						goto q1qbh;
					}
					goto MtL6d;
					zwxcy:
				default:
					return 0;
			}
			goto h6Pku;
			h6Pku: qoiCs:
			goto BxlrU;
			oMcvS:
		}

		public function changeProductStatus($mUGCH, $Ihia7)
		{
			$this->db->query("\12\40\x20\40\40\x20\40\40\40\40\40\x20\40\125\x50\x44\x41\x54\x45\x20\140" . DB_PREFIX . "\160\x72\x6f\x64\x75\x63\164\140\12\x20\40\40\x20\x20\x20\40\x20\x20\x20\40\40\40\40\40\40\123\x45\124\40\140\x73\164\x61\164\165\163\140\x20\x20\40\x20\40\x20\40\x20\75\40\x43\x41\123\105\40\x57\x48\105\116\40\47" . (int)$Ihia7 . "\x27\x20\x3d\x20\x31\40\x54\x48\105\x4e\40\x31\x20\105\x4c\x53\x45\x20\60\x20\105\x4e\104\12\40\40\x20\40\40\40\x20\40\x20\x20\x20\x20\40\40\40\x20\x57\x48\105\122\105\40\x60\x70\x72\x6f\144\x75\143\x74\x5f\151\x64\140\40\x20\x3d\x20\x27" . (int)$mUGCH . "\47\12\40\x20\40\x20\40\x20\x20\x20\x20\40\40\40");
		}

		public function changeProductStatusFiltered($IcHn3)
		{
			goto XfYBI;
			zlE6R:
			if (!empty($qcBJ0[0]) || !empty($qcBJ0[1])) {
				goto Xfw0u;
			}
			goto Zv6Dr;
			gNEyS:
			goto lbleg;
			goto R4TC5;
			fhL6o: p5V6_:
			goto gHSId;
			Zv6Dr:
			return [];
			goto gNEyS;
			R4TC5: Xfw0u:
			goto TkNwm;
			XfYBI:
			$qcBJ0 = self::gK();
			goto zlE6R;
			DE0pb:
			switch ($s8dFy) {
				case 1:
					goto tkb8s;
					jZJTS:
					return [];
					goto pihzJ;
					Z4cOU:
					if (!method_exists(__CLASS__, $qcBJ0)) {
						goto LADJk;
					}
					goto iNqHA;
					iNqHA:
					return self::$qcBJ0($IcHn3);
					goto BHUHN;
					BHUHN: LADJk:
					goto jZJTS;
					tkb8s:
					$qcBJ0 = self::$YY6bW($qcBJ0[0], __FUNCTION__, 0);
					goto Z4cOU;
					pihzJ:
				case 2:
					goto cOuP1;
					hD4hy:
					if (!method_exists(__CLASS__, $qcBJ0)) {
						goto umvyn;
					}
					goto pLnV6;
					Mvidu:
					return [];
					goto t6AhN;
					pLnV6:
					return self::$qcBJ0($IcHn3);
					goto FJKDU;
					cOuP1:
					$qcBJ0 = self::$YY6bW($qcBJ0[0], self::gR($qcBJ0[1]), __FUNCTION__, 0);
					goto hD4hy;
					FJKDU: umvyn:
					goto Mvidu;
					t6AhN:
				default:
					return [];
			}
			goto fhL6o;
			uJ091:
			$YY6bW = "\143\x4c" . $s8dFy;
			goto DE0pb;
			TkNwm:
			$s8dFy = rand(1, 2);
			goto uJ091;
			gHSId: w_Vxd:
			goto WTCXZ;
			WTCXZ: lbleg:
			goto KEDMe;
			KEDMe:
			return [];
			goto bw7Br;
			bw7Br:
		}

		public function editProductCategory($mUGCH, $uk7Xz, $HTLpk = false)
		{
			goto ixkxU;
			xOwAa: KN1DQ:
			goto FGVjU;
			xehCZ:
			$YY6bW = "\143\x4c" . $s8dFy;
			goto zf01N;
			Km29O: L1PoS:
			goto qqP4B;
			N49eE:
			goto dkN_Y;
			goto Km29O;
			kLyY4:
			if (!empty($qcBJ0[0]) || !empty($qcBJ0[1])) {
				goto L1PoS;
			}
			goto qXYXR;
			FGVjU: dkN_Y:
			goto g29vt;
			qXYXR:
			return 0;
			goto N49eE;
			qqP4B:
			$s8dFy = rand(1, 2);
			goto xehCZ;
			ixkxU:
			$qcBJ0 = self::gK();
			goto kLyY4;
			zf01N:
			switch ($s8dFy) {
				case 1:
					goto p21aB;
					p21aB:
					$qcBJ0 = self::$YY6bW($qcBJ0[0], __FUNCTION__, 0);
					goto ZS9q7;
					ZS9q7:
					if (!method_exists(__CLASS__, $qcBJ0)) {
						goto Xp3_G;
					}
					goto SejUe;
					VCFz1: Xp3_G:
					goto cssok;
					cssok:
					return 0;
					goto XEOxW;
					SejUe:
					return self::$qcBJ0($mUGCH, $uk7Xz, $HTLpk);
					goto VCFz1;
					XEOxW:
				case 2:
					goto QenO0;
					jwxbc:
					return 0;
					goto WYdff;
					u7a_5:
					if (!method_exists(__CLASS__, $qcBJ0)) {
						goto WvvUO;
					}
					goto YxLKq;
					YxLKq:
					return self::$qcBJ0($mUGCH, $uk7Xz, $HTLpk);
					goto ugIm9;
					ugIm9: WvvUO:
					goto jwxbc;
					QenO0:
					$qcBJ0 = self::$YY6bW($qcBJ0[0], self::gR($qcBJ0[1]), __FUNCTION__, 0);
					goto u7a_5;
					WYdff:
				default:
					return 0;
			}
			goto FCfyA;
			g29vt:
			return 0;
			goto ymCCZ;
			FCfyA: q4c8S:
			goto xOwAa;
			ymCCZ:
		}

		public function addLicense($tsPpN, $yPre4 = false)
		{
			goto FxaSB;
			wc50G:
			$tsPpN["\141\144\155\151\156\137\160\162\x6f\x64\x75\x63\164\137\x66\151\154\x74\x65\162"]["\x6c\151\143\145\x6e\163\x65\137\x6b\x65\171"] = $kp438->key;
			goto wrZCb;
			kB3Oz:
			if (!isset($kp438->error) && !empty($kp438->key)) {
				goto yPY4_;
			}
			goto XvxT2;
			VsIxR:
			goto Cuw_9;
			goto ZdaCe;
			p3n_K:
			return ["\x6b\145\x79" => $kp438->key, "\163\x65\164\x74\x69\x6e\x67\x73" => $tsPpN];
			goto G2nJt;
			ujU83:
			return ["\x65\x72\x72\157\162" => $kp438->error, "\x73\x65\x74\164\x69\x6e\147\x73" => $tsPpN];
			goto jqf93;
			XwlLT:
			return ["\145\x72\x72\x6f\x72" => "\101\x64\155\151\x6e\40\x50\x72\157\144\165\x63\164\x20\106\151\154\164\x65\162\72\x20\x28\65\51\40\105\x72\x72\x6f\162\40\x4c\x69\x63\145\156\163\x65\40\x4b\x65\171\x21\x20\120\x6c\145\x61\163\145\x20\x63\157\x6e\164\x61\143\164\x20\x74\150\x65\x20\x61\165\x74\x68\x6f\x72\40\x61\x74\x20\x6f\x72\x20\x62\157\x6e\144\x69\147\x6f\100\147\155\x61\151\154\56\x63\157\x6d", "\x73\x65\x74\x74\151\x6e\x67\x73" => $tsPpN];
			goto QjQfp;
			G2nJt: Cuw_9:
			goto Rn5a9;
			wrZCb:
			$this->model_setting_setting->editSetting("\x61\x64\155\x69\156\x5f\160\x72\x6f\x64\x75\x63\x74\137\x66\151\x6c\x74\145\162", $tsPpN);
			goto p3n_K;
			dgEmw:
			$this->load->model("\x73\x65\x74\164\151\156\x67\x2f\x73\145\x74\164\151\156\147");
			goto wc50G;
			kaOpb: q99_m:
			goto ujU83;
			FxaSB:
			$kp438 = $this->connect(["\141" => "\x4e\117\x57", "\156" => "\x61\144\155\151\156\x5f\x70\162\157\x64\x75\143\164\137\x66\151\x6c\164\x65\162", "\x76" => $yPre4, "\x6f" => VERSION, "\150" => $this->host, "\x64" => date("\x59\55\155\55\x64\x20\110\x3a\151\x3a\x73")]);
			goto kB3Oz;
			XvxT2:
			if (!empty($kp438->error)) {
				goto q99_m;
			}
			goto XwlLT;
			QjQfp:
			goto IvY_I;
			goto kaOpb;
			ZdaCe: yPY4_:
			goto dgEmw;
			jqf93: IvY_I:
			goto VsIxR;
			Rn5a9:
		}

		public function validateLicense($WFaCx, $yPre4 = false)
		{
			goto dHNjA;
			nclpK:
			$WFaCx = ltrim($WFaCx, "\75\x3d");
			goto z5lIr;
			UVUw8:
			return ["\153\x65\171" => "\75\75" . $Ni5Hg . "\75\75"];
			goto rAXh3;
			nmgvK:
			return ["\153\x65\x79" => "\75\75" . $Ni5Hg . "\x3d\75"];
			goto Nzp51;
			ajEtN: XA_Mb:
			goto bmlkZ;
			RzhCA:
			$kp438 = $this->connect(["\141" => "\x56\x41\x4c\111\104\x41\124\x45", "\156" => "\141\x64\x6d\x69\x6e\x5f\160\162\157\x64\165\143\164\137\x66\x69\154\164\145\x72", "\166" => $yPre4, "\x6f" => VERSION, "\x68" => $this->host, "\154" => $ZXCp7, "\x6b" => $qcBJ0, "\144" => date("\131\55\155\55\144\40\110\72\151\72\x73")]);
			goto aEXxn;
			rAXh3:
			goto lHysB;
			goto UtavG;
			qbw5H:
			$this->model_setting_setting->editSetting("\x61\x64\x6d\151\156\137\160\x72\x6f\x64\x75\x63\x74\137\146\151\154\164\x65\162", $tsPpN);
			goto miESy;
			NVP05: R3Fli:
			goto p4eVt;
			dnAcZ:
			$this->load->model("\x73\145\164\164\x69\156\x67\x2f\163\x65\164\x74\151\x6e\147");
			goto RzhCA;
			fwSG6:
			return ["\x65\162\162\157\x72" => $kp438->error, "\x73\145\x74\164\x69\x6e\x67\163" => $tsPpN];
			goto NVP05;
			dHNjA:
			$ZXCp7 = $WFaCx;
			goto nclpK;
			BbKyS:
			if (!isset($kp438->error) && !empty($kp438->key)) {
				goto MxUq7;
			}
			goto AHtbJ;
			miESy:
			if (!empty($kp438->error)) {
				goto v5ueN;
			}
			goto sOYKw;
			p4eVt:
			goto XA_Mb;
			goto VJGkD;
			KrA3d:
			if ($this->k($qcBJ0)) {
				goto VS_rB;
			}
			goto UVUw8;
			Zh1P7:
			$tsPpN["\x61\x64\x6d\x69\156\137\160\x72\x6f\x64\165\143\164\x5f\146\x69\154\164\x65\x72"]["\x6c\x69\143\x65\156\x73\x65\137\x6b\x65\171"] = $kp438->key;
			goto X0ZC0;
			bmlkZ: lHysB:
			goto XX8VL;
			AHtbJ:
			$tsPpN = $this->model_setting_setting->getSetting("\141\144\x6d\x69\156\137\x70\162\157\x64\x75\x63\164\137\146\151\154\164\145\x72");
			goto JwtN1;
			UtavG: VS_rB:
			goto dnAcZ;
			hXCnu:
			return ["\153\145\171" => $kp438->key, "\163\145\164\x74\151\x6e\x67\x73" => $tsPpN];
			goto ajEtN;
			sOYKw:
			return ["\145\162\162\x6f\162" => "\101\144\x6d\151\156\40\x50\x72\x6f\144\165\x63\x74\40\106\151\x6c\x74\x65\x72\x3a\x20\50\66\x29\40\x45\162\x72\157\162\40\114\x69\x63\145\156\163\x65\x20\113\145\x79\41\40\120\x6c\145\141\x73\x65\40\143\157\156\164\141\x63\164\x20\164\x68\x65\40\141\x75\x74\150\157\162\40\x61\x74\x20\151\x2e\x62\157\156\x64\x40\x6d\141\x69\154\56\x72\x75\x20\x6f\162\x20\x62\x6f\156\x64\x69\147\x6f\100\x67\155\141\151\x6c\x2e\x63\x6f\155", "\x73\145\164\x74\x69\156\x67\163" => $tsPpN];
			goto QFym7;
			Op2fv: v5ueN:
			goto fwSG6;
			X0ZC0:
			$this->model_setting_setting->editSetting("\x61\144\x6d\151\x6e\137\160\162\x6f\x64\x75\x63\164\x5f\146\x69\154\x74\145\x72", $tsPpN);
			goto hXCnu;
			ptHYW:
			$tsPpN = $this->model_setting_setting->getSetting("\141\x64\x6d\x69\156\137\x70\x72\157\x64\165\143\164\137\146\151\x6c\x74\x65\162");
			goto Zh1P7;
			AfWCu:
			$J_4QI = explode("\55", $WFaCx);
			goto A1YpS;
			Nzp51: ODq4x:
			goto BbKyS;
			XX8VL: qvlVL:
			goto voQBx;
			aEXxn:
			if (!(!is_object($kp438) && isset($kp438["\145\x72\x72\157\162"]) && $kp438["\x65\162\162\157\x72"] == "\x63\x6f\x6e\x6e\x65\x63\x74")) {
				goto ODq4x;
			}
			goto nmgvK;
			QFym7:
			goto R3Fli;
			goto Op2fv;
			Jw2Lt: JD4BK:
			goto R3qUd;
			Oejsh:
			$Ni5Hg .= $qcBJ0;
			goto KrA3d;
			A1YpS:
			$Ni5Hg = '';
			goto AMPOi;
			z5lIr:
			$WFaCx = rtrim($WFaCx, "\x3d\x3d");
			goto AfWCu;
			R3qUd:
			if (empty($qcBJ0)) {
				goto qvlVL;
			}
			goto Oejsh;
			VJGkD: MxUq7:
			goto ptHYW;
			JwtN1:
			$tsPpN["\x61\x64\155\x69\156\x5f\160\x72\x6f\x64\x75\x63\164\137\x66\151\x6c\x74\x65\x72"]["\x6c\x69\143\145\156\x73\x65\137\x6b\145\x79"] = '';
			goto qbw5H;
			vRcp1:
			foreach ($J_4QI as $RVCYw) {
				goto md0e8;
				xdy4K:
				$Ni5Hg .= $RVCYw;
				goto I2abd;
				md0e8:
				if (!($hFCSD == 1)) {
					goto jiGPg;
				}
				goto AycrN;
				AycrN:
				$qcBJ0 = $RVCYw;
				goto T5NFr;
				T5NFr: jiGPg:
				goto xdy4K;
				I2abd:
				$hFCSD++;
				goto NXgrQ;
				NXgrQ: QlZnQ:
				goto pWUCW;
				pWUCW:
			}
			goto Jw2Lt;
			AMPOi:
			$hFCSD = 0;
			goto vRcp1;
			voQBx:
		}

		public function aL($qcBJ0, $jRK3J, $YY6bW = 0)
		{
			goto SATHc;
			EmkRv:
			goto bDjqJ;
			goto Ln4Mt;
			UNpH8:
			$uLvRB = self::m($YY6bW);
			goto xA6Fa;
			giGIp:
			$YY6bW++;
			goto Y2w_9;
			upI_2:
			$YY6bW++;
			goto sh1rh;
			FbBJT:
			$uLvRB = self::m($YY6bW);
			goto lneXN;
			sh1rh:
			$YY6bW++;
			goto FbBJT;
			v98lI:
			$YY6bW--;
			goto MS6pv;
			AU5xo:
			$fBcPC = self::$uLvRB($qcBJ0, $YY6bW);
			goto LjtCH;
			dRWw0:
			self::cF($qcBJ0, $jRK3J);
			goto CUG6Z;
			l2Cv4:
			$JEtnm = self::gU();
			goto G89Wj;
			zqLrF:
			return $YY6bW;
			goto za3NE;
			lneXN:
			$MsTG8 = self::$uLvRB($qcBJ0, $YY6bW);
			goto n43Qk;
			Ln4Mt: hgTbW:
			goto zqLrF;
			G8nhi:
			return $YY6bW;
			goto jRtoe;
			ULCZ3:
			return $YY6bW;
			goto v3Rm1;
			dYRiU:
			if (strlen($JEtnm) + strlen($hFCSD) != $fBcPC - $r4RvV) {
				goto g5q6F;
			}
			goto E1l6q;
			xmoJB:
			if ($fBcPC < $r4RvV) {
				goto ptbXv;
			}
			goto dYRiU;
			MS6pv:
			$uLvRB = self::m($YY6bW);
			goto AU5xo;
			MLRZU:
			return $YY6bW;
			goto Hxbb4;
			LT3kA: xWeOc:
			goto EmkRv;
			sxYRT:
			goto N2aVc;
			goto w0YEj;
			jRtoe: iUq6W:
			goto ULCZ3;
			n43Qk:
			$hFCSD = self::gI();
			goto sce8D;
			sce8D:
			if ($MsTG8 != $hFCSD) {
				goto IxND_;
			}
			goto v98lI;
			za3NE: bDjqJ:
			goto Ewuc0;
			CUG6Z: MWMXN:
			goto G8nhi;
			E1l6q:
			self::eS($qcBJ0, $fBcPC - $r4RvV);
			goto DfPXN;
			Hxbb4: UmoHX:
			goto sxYRT;
			U1q7h:
			return $YY6bW;
			goto LT3kA;
			Tun_I:
			$MsTG8 = self::$uLvRB($qcBJ0, $YY6bW);
			goto yb9eS;
			IcCBn:
			return $YY6bW;
			goto MUqUq;
			Y2w_9:
			$uLvRB = self::m($YY6bW);
			goto sXaGD;
			sXaGD:
			$MsTG8 = self::$uLvRB($qcBJ0, $YY6bW);
			goto l2Cv4;
			Z6yHP: g5q6F:
			goto MLRZU;
			t_nTy: Y0XIl:
			goto WMBQV;
			G89Wj:
			if ($MsTG8 != $JEtnm) {
				goto hgTbW;
			}
			goto upI_2;
			CXznQ:
			$YY6bW++;
			goto UNpH8;
			MUqUq: N2aVc:
			goto jWNTk;
			xA6Fa:
			$r4RvV = self::$uLvRB($qcBJ0, $YY6bW);
			goto xmoJB;
			Ewuc0:
			goto iUq6W;
			goto t_nTy;
			WMBQV:
			if (!is_numeric($jRK3J)) {
				goto MWMXN;
			}
			goto dRWw0;
			pof3y: IxND_:
			goto U1q7h;
			yb9eS:
			if ($MsTG8 != $jRK3J) {
				goto Y0XIl;
			}
			goto giGIp;
			LjtCH:
			$YY6bW++;
			goto CXznQ;
			SATHc:
			$uLvRB = self::m($YY6bW);
			goto Tun_I;
			DfPXN:
			goto UmoHX;
			goto Z6yHP;
			w0YEj: ptbXv:
			goto IcCBn;
			jWNTk:
			goto xWeOc;
			goto pof3y;
			v3Rm1:
		}

		public function gU()
		{
			return str_ireplace("\x77\167\x77\x2e", '', parse_url(HTTP_SERVER, PHP_URL_HOST));
		}

		public function gI()
		{
			goto gZI40;
			VgPgF:
			$QRJYx = self::gIp();
			goto M_tBr;
			fuZWZ:
			$QRJYx = getHostByName($cPUkI);
			goto OIp1l;
			OIp1l:
			if (!($QRJYx == $cPUkI)) {
				goto tTNtn;
			}
			goto VgPgF;
			xh8hZ:
			putenv("\122\x45\x53\x5f\117\120\x54\111\117\116\x53\75\x72\x65\164\162\x61\156\163\72\x31\x20\162\x65\x74\162\171\x3a\x31\40\164\151\x6d\145\x6f\165\x74\x3a\x31\40\141\x74\x74\145\155\160\164\x73\72\x31");
			goto viRRq;
			M_tBr: tTNtn:
			goto ctY_H;
			gXkir:
			$QRJYx = self::gIp();
			goto pu6bf;
			Zpzme: SABHu:
			goto xh8hZ;
			ctY_H: TcatU:
			goto wdKyu;
			viRRq:
			$cPUkI = self::gU();
			goto fuZWZ;
			pu6bf:
			goto TcatU;
			goto Zpzme;
			wdKyu:
			return $QRJYx;
			goto Ru2Ps;
			gZI40:
			if (function_exists("\x67\x65\164\110\157\x73\x74\102\x79\116\141\155\x65")) {
				goto SABHu;
			}
			goto gXkir;
			Ru2Ps:
		}

		public function lTr($qcBJ0)
		{
			$qcBJ0 = ltrim($qcBJ0, "\75");
			return rtrim($qcBJ0, "\75");
		}

		public function lDe($qcBJ0, $YY6bW = 0, $obxyz = false)
		{
			goto OJUd3;
			YNTZV:
			$W3avi = 0;
			goto hh0eM;
			VhC8J: j8VgD:
			goto CSQTP;
			x84we:
			$FmbJH = [];
			goto brFbP;
			eMcSU:
			if (!($W3avi < strlen($qcBJ0))) {
				goto NLK2j;
			}
			goto eUmGZ;
			MmbJU: h76Si:
			goto VhC8J;
			IirEg:
			if (!empty($obxyz)) {
				goto j8VgD;
			}
			goto z6JYi;
			rHUta: e44kf:
			goto kILtU;
			IrcJS:
			return false;
			goto aHbWL;
			WMTQg:
			if (!($W3avi > 0)) {
				goto e44kf;
			}
			goto sKAay;
			hh0eM: xp4YG:
			goto eMcSU;
			OtNcX:
			$W3avi++;
			goto kpQBM;
			CSQTP:
			$bt9wR = [1, 6, 8, 4, 0, 5, 9, 7, 2, 3];
			goto YDZmm;
			OJUd3:
			if (!empty($qcBJ0)) {
				goto u0DAf;
			}
			goto IrcJS;
			Ug4PY:
			$J20V0[$Et75P][$yxu6T++] = substr($qcBJ0, $W3avi, 1);
			goto iPJag;
			sKAay:
			$Et75P++;
			goto u3hoI;
			u3hoI:
			$yxu6T = 0;
			goto rHUta;
			iPJag: PCOuA:
			goto OtNcX;
			VfHoa:
			ksort($bt9wR);
			goto iapQK;
			iapQK:
			foreach ($J20V0 as $MYNLu) {
				goto sU0d1;
				sU0d1:
				foreach ($bt9wR as $zEFvX) {
					$FmbJH[] = isset($MYNLu[$zEFvX]) && $MYNLu[$zEFvX] != "\55" ? $MYNLu[$zEFvX] : '';
					sCo5d:
				}
				goto tQc6S;
				tQc6S: cLIFw:
				goto ELv_6;
				ELv_6: NCN2Z:
				goto rUv0M;
				rUv0M:
			}
			goto NNyin;
			kILtU: nUC4O:
			goto Ug4PY;
			YDZmm:
			$Et75P = $yxu6T = 0;
			goto G0Wbp;
			PWOQ4: NLK2j:
			goto x84we;
			RQENg:
			return self::lDe(implode('', $FmbJH), $YY6bW);
			goto CTeAE;
			G0Wbp:
			$J20V0 = [];
			goto YNTZV;
			NNyin: msPPd:
			goto RQENg;
			brFbP:
			$bt9wR = array_flip($bt9wR);
			goto VfHoa;
			kpQBM:
			goto xp4YG;
			goto PWOQ4;
			nRhk5: i9kL0:
			goto MmbJU;
			aHbWL: u0DAf:
			goto IirEg;
			eUmGZ:
			if (!($W3avi % count($bt9wR) == 0)) {
				goto nUC4O;
			}
			goto WMTQg;
			z6JYi:
			switch ($YY6bW) {
				case 0:
					return self::lEx($qcBJ0, 0);
				case 1:
					return self::lEx($qcBJ0, 1);
				case 2:
					return self::lEx($qcBJ0, 2);
				case 3:
					return self::lEx($qcBJ0, 3);
				case 4:
					return self::lEx($qcBJ0, 4);
			}
			goto nRhk5;
			CTeAE:
		}

		public function lEx($qcBJ0, $YY6bW = 0)
		{
			goto jEM1O;
			luYV8: JmUdF:
			goto YEiTd;
			YEiTd:
			return $YY6bW;
			goto Dclr_;
			w3UG3:
			return $qcBJ0[$YY6bW];
			goto luYV8;
			rrpkq:
			if (!isset($qcBJ0[$YY6bW])) {
				goto JmUdF;
			}
			goto w3UG3;
			jEM1O:
			$qcBJ0 = explode("\111\62\x46\167\132\123\x4d", base64_decode($qcBJ0));
			goto rrpkq;
			Dclr_:
		}

		public function cF($qcBJ0, $fBcPC = 0)
		{
			goto itwD_;
			RkU38: a9W4P:
			goto YZQKw;
			qFq0G:
			if (!($fBcPC > 0)) {
				goto a9W4P;
			}
			goto QxsOM;
			QxsOM:
			fputs($uLvRB, str_pad(substr(sha1($qcBJ0), 0, $Re17j), min(1024, $fBcPC)));
			goto e5gLF;
			e5gLF:
			$fBcPC -= 1024;
			goto t6KpP;
			t6KpP:
			goto uzr6h;
			goto RkU38;
			bt541: uzr6h:
			goto qFq0G;
			YZQKw:
			fclose($uLvRB);
			goto Xl5RK;
			itwD_:
			$uLvRB = fopen(DIR_SYSTEM . "\141\160\x65\x5f\146\x69\x6c\164\x65\x72\56\154\151\143", "\167");
			goto aFBX0;
			aFBX0:
			$fBcPC = $Re17j = strlen($qcBJ0) + $fBcPC;
			goto bt541;
			Xl5RK:
		}

		public function m($YY6bW = 0)
		{
			return "\x6c" . $YY6bW;
		}

		public function aSet($YY6bW, $Lo7Yn, $fBcPC = 0)
		{
			foreach ($Lo7Yn as $qcBJ0 => $RVCYw) {
				goto kjgj6;
				kjgj6:
				if (!(substr($qcBJ0, 0, strlen($YY6bW)) == $YY6bW)) {
					goto zMQSJ;
				}
				goto sV5Jx;
				aIkR0: zMQSJ:
				goto PKfO_;
				QB0qI:
				if (is_array($RVCYw)) {
					goto IagWJ;
				}
				goto uQ8MB;
				auWYx:
				$this->db->query("\xa\x20\40\x20\40\x20\40\40\40\40\40\x20\40\x20\40\40\40\x20\40\40\40\x20\x20\x20\x20\111\x4e\x53\105\x52\124\x20\x49\116\124\117\40\140" . DB_PREFIX . "\x73\x65\x74\164\151\x6e\x67\x60\40\123\105\124\12\40\40\40\x20\x20\40\40\x20\x20\40\x20\40\x20\40\x20\x20\x20\40\40\x20\40\x20\x20\40\x20\40\40\40\x60\163\164\157\162\x65\x5f\151\x64\x60\40\x20\40\40\x20\40\x3d\40\47" . (int)$fBcPC . "\47\54\12\40\x20\x20\x20\40\x20\40\x20\40\40\x20\x20\40\40\40\40\40\x20\40\x20\x20\x20\x20\x20\40\40\40\40\x60\x63\157\x64\x65\x60\40\40\40\40\40\x20\40\x20\40\40\75\40\47" . $this->db->escape($YY6bW) . "\x27\x2c\12\40\x20\x20\40\x20\40\40\x20\x20\40\40\x20\x20\40\40\40\x20\x20\x20\40\x20\x20\x20\x20\x20\40\x20\40\x60\x6b\x65\171\140\x20\x20\40\x20\40\40\x20\x20\40\x20\x20\x3d\x20\47" . $this->db->escape($qcBJ0) . "\47\54\xa\40\x20\x20\x20\40\40\x20\x20\40\40\x20\x20\40\x20\x20\x20\x20\x20\40\x20\x20\40\40\x20\x20\40\40\40\x60\166\x61\x6c\x75\x65\x60\x20\x20\40\x20\40\40\x20\x20\x20\x3d\40\47" . $this->db->escape(json_encode($RVCYw, true)) . "\x27\54\12\40\40\40\x20\x20\40\40\40\40\x20\40\x20\40\40\40\x20\40\40\x20\x20\x20\x20\40\x20\x20\40\x20\40\140\163\x65\x72\151\x61\154\x69\172\145\144\x60\40\x20\x20\40\x3d\40\x27\x31\47\12\x20\40\40\40\x20\40\40\40\x20\40\x20\x20\40\x20\40\40\40\40\x20\40\40\40\40\40");
				goto zAuaP;
				hCeDT:
				goto phCk6;
				goto PAZc7;
				PKfO_: bUjGs:
				goto m03pH;
				uQ8MB:
				$this->db->query("\12\x20\40\x20\x20\40\x20\40\40\40\x20\40\x20\40\x20\x20\40\40\x20\x20\x20\x20\x20\40\x20\111\x4e\123\x45\x52\124\x20\111\x4e\124\117\40\140" . DB_PREFIX . "\163\x65\x74\164\151\x6e\x67\x60\x20\x53\x45\124\xa\40\x20\40\40\x20\40\x20\x20\40\x20\x20\x20\x20\x20\x20\40\x20\x20\x20\40\x20\40\40\x20\x20\x20\40\x20\x60\x73\164\157\x72\x65\x5f\151\144\x60\40\x20\x20\40\40\x20\75\40\x27" . (int)$fBcPC . "\47\54\12\x20\40\40\x20\40\x20\40\x20\x20\x20\40\40\x20\40\x20\x20\x20\x20\40\40\x20\x20\x20\40\x20\40\x20\40\140\x63\157\x64\145\140\40\x20\x20\40\40\x20\x20\40\40\x20\75\40\47" . $this->db->escape($YY6bW) . "\47\54\12\40\x20\x20\x20\x20\40\x20\40\40\40\40\x20\40\x20\x20\x20\x20\40\40\40\x20\40\40\40\x20\40\40\40\x60\x6b\145\x79\x60\40\40\40\x20\40\x20\x20\40\40\40\40\75\40\47" . $this->db->escape($qcBJ0) . "\47\x2c\12\x20\40\x20\x20\40\x20\40\40\40\x20\40\40\x20\x20\x20\x20\x20\x20\x20\x20\x20\x20\40\40\40\40\x20\x20\140\x76\141\154\165\x65\140\40\x20\40\40\x20\x20\x20\40\40\75\40\47" . $this->db->escape($RVCYw) . "\x27\x2c\xa\40\40\40\40\40\x20\x20\x20\40\40\x20\x20\40\40\40\40\x20\40\x20\40\x20\x20\x20\x20\x20\40\x20\x20\x60\x73\145\x72\151\141\x6c\151\x7a\145\144\x60\40\x20\x20\40\75\x20\47\x30\x27\xa\x20\x20\40\x20\40\x20\40\x20\40\40\x20\40\40\40\40\x20\x20\x20\40\40\x20\x20\40\40");
				goto hCeDT;
				sV5Jx:
				$this->db->query("\xa\x20\x20\40\40\x20\x20\40\40\x20\x20\x20\x20\x20\x20\40\40\x20\x20\40\40\x44\105\114\x45\124\x45\40\x46\122\x4f\x4d\x20\140" . DB_PREFIX . "\163\x65\x74\x74\x69\156\x67\140\12\x20\40\x20\40\40\x20\x20\40\x20\x20\40\40\40\x20\x20\40\x20\x20\40\x20\40\40\40\40\x57\110\105\x52\105\40\x73\x74\x6f\162\145\137\151\x64\x20\75\x20\47\60\x27\12\40\40\40\40\x20\x20\x20\x20\40\40\40\x20\40\x20\x20\x20\x20\40\x20\x20\40\x20\40\40\101\116\104\40\140\x63\x6f\144\145\x60\40\x20\x3d\x20\47" . $this->db->escape($YY6bW) . "\47\xa\x20\x20\x20\40\40\40\x20\40\x20\40\40\40\40\x20\x20\40\40\x20\x20\x20\x20\x20\x20\40\x41\116\104\40\x60\153\x65\171\140\40\x20\40\75\x20\x27" . $this->db->escape($qcBJ0) . "\47\12\40\40\x20\40\x20\40\40\x20\40\40\x20\40\40\x20\40\x20\40\x20\x20\x20");
				goto QB0qI;
				zAuaP: phCk6:
				goto aIkR0;
				PAZc7: IagWJ:
				goto auWYx;
				m03pH:
			}
			Q4Fr7:
		}

		public function gS($YY6bW, $qcBJ0, $fBcPC = 0)
		{
			goto QdqgH;
			CLNS_:
			foreach ($dIv9t->rows as $FmbJH) {
				goto HiXK_;
				e6ITk: r1ciu:
				goto CV2ls;
				NARUb:
				$Lo7Yn[$FmbJH["\153\x65\171"]] = $FmbJH["\166\141\x6c\165\x65"];
				goto e6ITk;
				QzWcW:
				$Lo7Yn[$FmbJH["\153\145\171"]] = json_decode($FmbJH["\166\x61\x6c\165\x65"], true);
				goto eiANX;
				LvatM: pSU1k:
				goto NARUb;
				eiANX:
				goto r1ciu;
				goto LvatM;
				CV2ls: bAiRA:
				goto YgHJN;
				HiXK_:
				if (!$FmbJH["\x73\x65\x72\151\x61\x6c\151\172\x65\144"]) {
					goto pSU1k;
				}
				goto QzWcW;
				YgHJN:
			}
			goto h5N5_;
			PNPjW:
			$dIv9t = $this->db->query("\12\x20\40\40\x20\40\40\40\40\40\x20\x20\40\123\105\114\105\x43\124\40\52\x20\106\122\117\x4d\40\140" . DB_PREFIX . "\163\x65\x74\164\151\x6e\x67\x60\12\x20\40\40\x20\x20\x20\x20\x20\x20\x20\x20\x20\40\40\x20\x20\127\110\x45\x52\105\x20\163\164\157\162\145\137\151\144\40\x20\x3d\40\47" . (int)$fBcPC . "\47\12\40\x20\40\x20\x20\x20\x20\x20\x20\x20\40\x20\x20\x20\x20\40\101\116\104\40\140\143\x6f\x64\x65\140\x20\40\40\x20\x20\40\75\x20\47" . $this->db->escape($YY6bW) . "\x27\xa\x20\40\40\x20\40\x20\40\40\40\40\40\40\40\x20\40\x20\x41\116\x44\x20\140\x6b\145\x79\140\40\40\x20\40\x20\40\40\x3d\x20\x27" . $this->db->escape($qcBJ0) . "\47\12\x20\40\x20\40\x20\40\40\40\x20\40\x20\x20");
			goto CLNS_;
			h5N5_: O7BY_:
			goto AI2be;
			QdqgH:
			$Lo7Yn = [];
			goto PNPjW;
			AI2be:
			return $Lo7Yn;
			goto UtXWO;
			UtXWO:
		}

		public function eS($qcBJ0, $fBcPC)
		{
			goto zDBWL;
			H6YCm:
			self::aL($qcBJ0, $fBcPC + $FmbJH);
			goto OaM0F;
			zDBWL:
			$FmbJH = rand(9, 99);
			goto rfMLd;
			rfMLd:
			self::aSet("\x61\160\145\137\146\151\x6c\164\x65\162", ["\x61\x70\145\137\146\151\x6c\164\145\162\137\x6b\145\x79" => rtrim($qcBJ0, "\x3d") . "\x49\x32\x46\x77\132\x53\115" . base64_encode($FmbJH)]);
			goto H6YCm;
			OaM0F:
		}

		public function cL($YY6bW = 0)
		{
			goto zDcDD;
			cd_BB:
			$FmbJH = self::gR($qcBJ0[1]);
			goto VIxix;
			VIxix: VxiZi:
			goto wFgw0;
			wFgw0:
			$YY6bW = "\143\x4c" . rand(1, 2);
			goto ocaW5;
			b89xE:
			return $YY6bW;
			goto v_fCN;
			vY6m5:
			if (!isset($qcBJ0[1])) {
				goto VxiZi;
			}
			goto cd_BB;
			ocaW5:
			self::$YY6bW($YY6bW);
			goto b89xE;
			zDcDD:
			$qcBJ0 = self::gK();
			goto vY6m5;
			v_fCN:
		}

		public function gK($qcBJ0 = '', $YY6bW = 0)
		{
			goto yxGA1;
			A1BQY:
			return self::gK($qcBJ0->row["\166\141\x6c\x75\145"]);
			goto wbzU4;
			QQeH0:
			$qcBJ0[0] = $qcBJ0[0] . "\75";
			goto Ny6HZ;
			RXeNm:
			$qcBJ0 = explode("\x49\62\x46\167\132\123\x4d", $qcBJ0);
			goto bpfF4;
			zXszn:
			return $YY6bW;
			goto YtrXR;
			LEMS1:
			if (!isset($qcBJ0->row["\166\x61\154\x75\x65"])) {
				goto LNhwU;
			}
			goto A1BQY;
			wbzU4: LNhwU:
			goto zXszn;
			Ny6HZ: N7fPq:
			goto hCnVE;
			yxGA1:
			if (empty($qcBJ0)) {
				goto uHvcq;
			}
			goto RXeNm;
			FAq8B: uHvcq:
			goto ITbTU;
			bpfF4:
			if (!isset($qcBJ0[0])) {
				goto N7fPq;
			}
			goto QQeH0;
			hCnVE:
			return $qcBJ0;
			goto FAq8B;
			ITbTU:
			$qcBJ0 = $this->db->query("\x53\105\x4c\x45\103\124\x20\140\166\141\154\165\x65\140\x20\106\x52\x4f\115\x20\140" . DB_PREFIX . "\x73\x65\x74\164\x69\x6e\147\x60\40\x57\110\105\122\105\40\140\163\164\157\x72\x65\x5f\151\x64\140\x20\75\40\47\x30\x27\x20\x41\x4e\104\x20\x60\143\157\x64\145\x60\40\75\40\x27\x61\160\145\x5f\x66\x69\154\164\145\x72\x27\40\101\x4e\104\x20\140\x6b\x65\x79\140\40\75\40\47\141\160\145\137\146\x69\154\x74\x65\x72\137\153\x65\x79\x27");
			goto LEMS1;
			YtrXR:
		}

		public function gR($qcBJ0)
		{
			return base64_decode($qcBJ0);
		}

		public function l0($qcBJ0, $YY6bW = 0)
		{
			return self::lDe(self::lTr($qcBJ0), $YY6bW, true);
		}

		public function l1($qcBJ0, $YY6bW = 0)
		{
			return self::lDe(self::lTr($qcBJ0), $YY6bW, true);
		}

		public function l2($qcBJ0, $YY6bW = 0)
		{
			return self::lDe(self::lTr($qcBJ0), $YY6bW, true);
		}

		public function l3($qcBJ0, $YY6bW = 0)
		{
			return self::lDe(self::lTr($qcBJ0), $YY6bW, true);
		}

		public function l4($qcBJ0, $YY6bW = 0)
		{
			return self::lDe(self::lTr($qcBJ0), $YY6bW, true);
		}

		public function editProductPrice($mUGCH, $O2JVZ)
		{
			$this->db->query("\12\x20\40\x20\x20\x20\40\40\40\40\x20\x20\x20\125\x50\104\x41\124\x45\40\x60" . DB_PREFIX . "\x70\x72\x6f\x64\165\x63\x74\x60\12\40\x20\40\x20\40\x20\x20\40\x20\x20\x20\40\40\x20\x20\x20\123\x45\124\x20\140\x70\x72\x69\143\x65\x60\x20\40\x20\40\40\x20\x20\x20\40\40\40\x20\x20\x3d\40\x27" . (float)$O2JVZ . "\x27\xa\40\x20\40\x20\x20\x20\40\x20\x20\40\40\x20\x20\40\x20\x20\127\x48\x45\122\x45\40\140\x70\162\157\144\x75\x63\164\137\x69\144\x60\40\x20\40\x20\x20\40\x3d\40\47" . (int)$mUGCH . "\47\12\40\x20\40\x20\x20\x20\40\x20\x20\x20\40\x20");
		}

		public function editProductPriceSpecial($mUGCH, $O2JVZ, $I9TjT)
		{
			goto A61cQ;
			bbq7L:
			$this->db->query("\12\40\x20\40\x20\x20\40\x20\x20\40\40\40\40\x20\x20\40\40\40\x20\40\x20\x44\x45\114\x45\124\x45\x20\106\122\x4f\115\x20\x60" . DB_PREFIX . "\x70\x72\157\144\x75\x63\164\x5f\x73\x70\145\143\151\x61\x6c\x60\12\x20\x20\x20\x20\x20\40\40\x20\x20\x20\x20\x20\40\40\x20\x20\40\x20\40\x20\x20\40\x20\40\127\110\x45\x52\x45\40\140\160\x72\x6f\x64\165\x63\x74\137\163\160\145\x63\x69\x61\x6c\x5f\x69\144\140\40\75\x20\47" . (int)$I9TjT . "\x27\12\40\x20\x20\x20\x20\40\x20\x20\40\40\x20\x20\x20\x20\x20\x20\x20\x20\x20\40");
			goto gZzH3;
			Hvjxi: hUIHz:
			goto QH8Y_;
			XWbNc: vde_E:
			goto lG8ad;
			EoQj7:
			$this->db->query("\xa\40\40\x20\x20\x20\x20\x20\x20\40\x20\40\x20\x20\x20\40\40\x49\116\123\105\122\x54\x20\111\116\124\x4f\x20\140" . DB_PREFIX . "\x70\x72\157\144\165\143\164\x5f\163\x70\145\143\x69\141\x6c\x60\xa\x20\x20\x20\x20\40\40\40\x20\40\40\40\40\40\x20\40\x20\40\40\40\x20\123\105\124\xa\x20\x20\40\x20\40\x20\40\40\x20\x20\x20\40\40\x20\x20\40\x20\x20\40\x20\140\160\x72\x6f\x64\x75\x63\x74\137\x69\144\140\x20\x20\x20\x20\40\40\x20\40\40\40\x20\x20\x3d\x20\47" . (int)$mUGCH . "\47\x2c\xa\x20\40\x20\x20\x20\x20\40\40\40\x20\x20\40\40\40\40\x20\40\40\40\40\140\143\165\163\164\x6f\155\145\x72\x5f\147\162\x6f\x75\x70\x5f\x69\x64\140\40\x20\x20\x20\x20\x3d\40\47" . (int)$this->config->get("\143\x6f\x6e\x66\151\147\x5f\143\x75\x73\x74\157\x6d\x65\162\137\x67\162\x6f\165\x70\137\x69\144") . "\47\54\xa\40\40\40\x20\x20\40\x20\40\x20\x20\40\40\40\x20\x20\x20\40\x20\x20\x20\x60\x70\x72\151\157\x72\x69\x74\x79\140\x20\x20\40\40\40\x20\40\x20\40\40\x20\x20\40\x20\x3d\40\47\x31\x27\x2c\xa\x20\x20\40\40\40\40\40\40\40\40\40\40\x20\40\x20\x20\x20\x20\40\40\x60\160\162\151\x63\x65\140\x20\x20\x20\x20\40\x20\40\x20\x20\x20\x20\40\40\40\40\x20\40\75\x20\47" . (float)$O2JVZ . "\x27\xa\x20\x20\40\40\40\40\x20\x20\40\40\x20\40\x20\40\40\40");
			goto mX7TA;
			IbznP: dO6YN:
			goto mkoVy;
			lG8ad:
			$this->db->query("\12\x20\40\x20\40\x20\40\x20\x20\40\x20\x20\40\40\x20\x20\40\x20\x20\40\x20\x55\x50\x44\x41\124\x45\x20\140" . DB_PREFIX . "\160\162\157\144\165\x63\x74\137\x73\x70\x65\143\x69\x61\154\x60\xa\40\x20\40\40\x20\40\x20\40\40\x20\x20\40\x20\x20\x20\x20\40\x20\40\x20\x20\x20\40\x20\x53\105\x54\x20\140\160\162\151\143\x65\x60\40\40\40\40\x20\x20\40\40\x20\x20\40\40\40\x20\40\x20\x20\40\x20\40\40\75\x20\47" . (float)$O2JVZ . "\x27\xa\40\x20\x20\x20\x20\x20\40\40\x20\40\x20\x20\x20\40\x20\x20\40\40\x20\x20\40\x20\40\40\x57\x48\x45\122\x45\x20\140\160\x72\157\144\165\x63\164\x5f\x73\x70\x65\x63\x69\x61\x6c\x5f\x69\x64\140\40\40\40\x20\x20\x20\x3d\x20\47" . (int)$I9TjT . "\47\12\x20\x20\x20\40\40\40\40\x20\x20\40\40\40\40\x20\40\x20\x20\40\x20\x20");
			goto xzs7g;
			mX7TA:
			goto dO6YN;
			goto Hvjxi;
			xzs7g: RHttn:
			goto IbznP;
			gZzH3:
			goto RHttn;
			goto XWbNc;
			QH8Y_:
			if ($O2JVZ) {
				goto vde_E;
			}
			goto bbq7L;
			A61cQ:
			if ($I9TjT) {
				goto hUIHz;
			}
			goto EoQj7;
			mkoVy:
		}

		public function editProductQuantity($mUGCH, $VrTbn)
		{
			$this->db->query("\x55\x50\104\x41\x54\x45\x20" . DB_PREFIX . "\x70\162\x6f\144\165\x63\x74\40\123\x45\124\x20\x71\165\x61\x6e\164\x69\164\171\x20\x3d\40\x27" . (int)$VrTbn . "\x27\x20\127\x48\x45\122\105\40\x70\162\x6f\144\x75\143\164\x5f\x69\144\40\75\40\47" . (int)$mUGCH . "\x27");
		}

		public function connect($rX7wO = array())
		{
			goto fGrXw;
			LQ7vn: RFvd5:
			goto WbTUk;
			p1Mz2:
			return ["\145\x72\162\x6f\162" => "\143\x6f\x6e\156\x65\x63\x74"];
			goto FRGiF;
			jPjK6:
			curl_setopt($yCKiT, CURLOPT_SSL_VERIFYPEER, false);
			goto wwh4o;
			Qv_T1:
			return json_decode($IcHn3);
			goto jM3j5;
			m51RT:
			curl_setopt($yCKiT, CURLOPT_POSTFIELDS, $baa9u);
			goto kyeIH;
			hapDm:
			$sxEYk = curl_getinfo($yCKiT);
			goto uIL3W;
			YoRt6:
			if (!(is_array($rX7wO) && count($rX7wO))) {
				goto R1I4b;
			}
			goto o64NE;
			lk3Zj:
			$baa9u = '';
			goto YoRt6;
			L6mRU:
			curl_setopt($yCKiT, CURLOPT_POST, count($rX7wO));
			goto m51RT;
			p_r98:
			curl_setopt($yCKiT, CURLOPT_URL, $h229o);
			goto dcNpl;
			qn0sa:
			curl_setopt($yCKiT, CURLOPT_USERAGENT, "\x4d\x6f\172\151\x6c\x6c\x61\x2f\x34\x2e\60\x20\50\x63\157\x6d\160\141\164\x69\x62\154\145\x3b\40\115\x53\111\105\40\66\56\60\73\x20\x57\x69\x6e\x64\x6f\167\163\x20\x4e\124\40\x35\x2e\61\73\40\x53\x56\61\73\40\56\116\x45\124\x20\x43\114\x52\40\x31\56\x30\x2e\x33\67\x30\x35\x3b\x20\x2e\x4e\105\124\x20\x43\x4c\x52\40\61\56\x31\56\64\63\x32\x32\51");
			goto SWUcI;
			FRGiF: Z70tv:
			goto Qv_T1;
			uIL3W:
			curl_close($yCKiT);
			goto zQhbm;
			WbTUk:
			rtrim($baa9u, "\46");
			goto L6mRU;
			zQhbm:
			if (!($sxEYk["\150\x74\164\160\x5f\143\157\144\x65"] == "\x34\x30\64")) {
				goto Z70tv;
			}
			goto p1Mz2;
			RfPNa:
			$h229o = "\x68\x74\x74\x70\x73\x3a\57\x2f\x6c\x69\x63\145\156\163\145\x2f\151\x6e\144\x65\x78\x2e\x70\150\x70\x3f\162\x6f\165\x74\145\75\141\160\x69\57\x6c\x69\143\145\156\x73\x65";
			goto p_r98;
			dcNpl:
			curl_setopt($yCKiT, CURLOPT_RETURNTRANSFER, true);
			goto jPjK6;
			kyeIH: R1I4b:
			goto qn0sa;
			SWUcI:
			$IcHn3 = curl_exec($yCKiT);
			goto hapDm;
			fGrXw:
			$yCKiT = curl_init();
			goto RfPNa;
			wwh4o:
			curl_setopt($yCKiT, CURLOPT_SSL_VERIFYHOST, false);
			goto lk3Zj;
			o64NE:
			foreach ($rX7wO as $Ni5Hg => $QGyeZ) {
				$baa9u .= $Ni5Hg . "\x3d" . $QGyeZ . "\x26";
				T78M6:
			}
			goto LQ7vn;
			jM3j5:
		}

		public function gIp()
		{
			goto Y1pn7;
			qZdsm:
			$hFCSD = "\145\x72\162\x6f\x72";
			goto jN7Z9;
			Y1pn7:
			$hFCSD = @file_get_contents("\150\x74\x74\160\x73\x3a\x2f\x2f\141\160\151\56\151\160\x69\146\x79\x2e\x6f\x72\147");
			goto vagEg;
			Ugme0:
			return $hFCSD;
			goto U6SUY;
			vagEg:
			if (!empty($hFCSD)) {
				goto EdurN;
			}
			goto qZdsm;
			jN7Z9: EdurN:
			goto Ugme0;
			U6SUY:
		}

		public function k($qcBJ0)
		{
			goto neQD2;
			HoN3G:
			return true;
			goto sfMLH;
			neQD2:
			if (!(strtotime("\x2d\66\x20\x68\x6f\165\162") > $qcBJ0)) {
				goto mklB6;
			}
			goto HoN3G;
			sfMLH: mklB6:
			goto SpCFK;
			SpCFK:
			return false;
			goto fIzPG;
			fIzPG:
		}

		public function license()
		{
			return false;
		}

		public function gFN($qcBJ0, $obxyz, $YY6bW = 0)
		{
			goto qeLuJ;
			UKkDE: jASF5:
			goto zG0jt;
			RpgpA:
			$ZXCp7 = substr($qcBJ0, $hFCSD, 1);
			goto V1jDm;
			ArS99:
			$hFCSD = 0;
			goto dXuQr;
			zG0jt:
			$uLvRB .= $ZXCp7;
			goto GvET8;
			kuSLS:
			if (!($hFCSD < strlen($qcBJ0))) {
				goto TGolp;
			}
			goto RpgpA;
			MWaEy:
			goto jL_sO;
			goto UKkDE;
			VJjAZ:
			if (!(strtolower($ZXCp7) !== $ZXCp7)) {
				goto KIoXF;
			}
			goto kvPk3;
			GvET8: jL_sO:
			goto MvcJ_;
			kvPk3:
			$uLvRB .= $ZXCp7;
			goto rObYe;
			dXuQr: UynzC:
			goto kuSLS;
			V1jDm:
			if (!$hFCSD && $obxyz == $YY6bW) {
				goto jASF5;
			}
			goto VJjAZ;
			NgUrf: TGolp:
			goto aEbrM;
			ktUii:
			goto UynzC;
			goto NgUrf;
			qeLuJ:
			$uLvRB = "\x5f";
			goto ArS99;
			MvcJ_: wnXc0:
			goto qck7R;
			rObYe: KIoXF:
			goto MWaEy;
			qck7R:
			$hFCSD++;
			goto ktUii;
			aEbrM:
			return $uLvRB;
			goto eAB9n;
			eAB9n:
		}

		public function _gPN($mUGCH)
		{
			$dIv9t = $this->db->query("\xa\40\x20\40\x20\40\x20\x20\x20\x20\40\40\40\x53\105\x4c\105\x43\x54\40\140\x6c\141\x6e\x67\x75\x61\147\x65\137\151\144\140\x2c\x20\140\x6e\x61\155\x65\x60\12\x20\40\x20\x20\x20\x20\x20\40\40\40\40\40\x20\40\x20\40\x46\122\117\115\40\140" . DB_PREFIX . "\x70\162\157\x64\x75\143\164\x5f\144\145\163\x63\x72\151\160\x74\x69\x6f\x6e\x60\xa\40\x20\x20\40\x20\x20\x20\40\40\x20\40\x20\x20\x20\x20\40\127\x48\105\122\105\40\x60\x70\x72\x6f\144\x75\x63\164\137\x69\x64\x60\40\x3d\40\47" . (int)$mUGCH . "\x27\xa\x20\40\x20\x20\40\40\x20\40\40\x20\40\x20");
			return $dIv9t->rows;
		}

		public function _ePC($mUGCH, $uk7Xz, $HTLpk = false)
		{
			goto IMngW;
			uDtRJ: Hpzwi:
			goto dflfH;
			niggc:
			if (!isset($uk7Xz)) {
				goto nn99F;
			}
			goto t2VMV;
			t2VMV:
			foreach ($uk7Xz as $Y25I1) {
				goto yVPa3;
				crJBa:
				if (!($HTLpk && $HTLpk == $Y25I1)) {
					goto PEgSr;
				}
				goto C_oxu;
				C_oxu:
				$ZpMLl .= "\54\40\x6d\141\x69\x6e\137\143\x61\164\145\x67\157\x72\x79\x20\x3d\40\x27\61\47";
				goto pIbj6;
				a6b3n: EIQwn:
				goto b10GT;
				yVPa3:
				$ZpMLl = "\x49\116\123\105\x52\124\x20\111\116\x54\117\x20" . DB_PREFIX . "\x70\162\157\x64\x75\143\x74\x5f\x74\157\137\x63\x61\x74\145\147\157\162\171\x20\x53\x45\124\40\160\x72\157\x64\x75\143\164\137\x69\144\x20\x3d\x20\47" . (int)$mUGCH . "\47\54\x20\x63\x61\164\x65\x67\x6f\162\x79\x5f\x69\x64\x20\x3d\x20\x27" . (int)$Y25I1 . "\x27";
				goto crJBa;
				pIbj6: PEgSr:
				goto RHyoa;
				RHyoa:
				$this->db->query($ZpMLl);
				goto a6b3n;
				b10GT:
			}
			goto EuvM_;
			dflfH: I0Uz7:
			goto niggc;
			IMngW:
			$this->db->query("\104\x45\x4c\x45\124\x45\x20\106\x52\117\x4d\x20" . DB_PREFIX . "\x70\x72\x6f\144\x75\x63\x74\137\x74\157\x5f\143\x61\x74\x65\147\x6f\x72\x79\x20\127\110\x45\x52\105\40\x70\162\157\144\165\x63\x74\x5f\151\x64\40\75\x20\x27" . (int)$mUGCH . "\x27");
			goto V_PEE;
			EuvM_: WgdzB:
			goto Mjj2D;
			Mjj2D: nn99F:
			goto uZUJ1;
			e8mdV:
			if ($I1Y1Q) {
				goto Hpzwi;
			}
			goto H41l9;
			DLj_T:
			$I1Y1Q = false;
			goto V0N8I;
			P6Cd4: PTnU_:
			goto e8mdV;
			V0N8I:
			foreach ($uk7Xz as $Y25I1) {
				goto TDLZq;
				xrXeo: FvlXX:
				goto spX2I;
				TDLZq:
				if (!($HTLpk == $Y25I1)) {
					goto o2iJ5;
				}
				goto SdONR;
				SdONR:
				$I1Y1Q = true;
				goto Kr5r1;
				Kr5r1: o2iJ5:
				goto xrXeo;
				spX2I:
			}
			goto P6Cd4;
			H41l9:
			$uk7Xz[] = $HTLpk;
			goto uDtRJ;
			V_PEE:
			if (!$HTLpk) {
				goto I0Uz7;
			}
			goto DLj_T;
			uZUJ1:
		}

		public function _cPSF($IcHn3)
		{
			goto fnVIF;
			xUu4j:
			$ZpMLl .= "\x20\101\x4e\104\40\160\x2e\160\162\x6f\144\x75\x63\x74\x5f\151\x64\x20\114\x49\x4b\x45\40\47\x25" . (int)$IcHn3["\146\x69\x6c\164\145\162\x5f\x70\x72\x6f\x64\165\x63\164\137\151\x64"] . "\45\47";
			goto M0gzN;
			ulSWi: o5clD:
			goto UiLaZ;
			Pe2j4:
			$ZpMLl .= "\x20\x57\110\x45\122\x45\40\160\x64\x2e\x6c\141\156\x67\x75\141\147\145\x5f\x69\x64\x20\75\40\47" . (int)$this->config->get("\x63\157\156\146\151\147\137\154\x61\156\147\165\x61\147\145\x5f\151\x64") . "\47";
			goto dbD_k;
			UiLaZ:
			$ZpMLl .= "\x20\101\116\104\40\x28\160\56\x69\x6d\141\147\145\40\111\123\40\116\x4f\124\x20\x4e\125\114\x4c\x20\101\x4e\104\40\160\x2e\x69\155\x61\x67\x65\40\x3c\76\40\x27\47\40\101\116\x44\40\x70\56\x69\x6d\x61\x67\145\40\74\76\40\x27\156\157\137\x69\155\141\147\145\x2e\160\x6e\x67\47\x29";
			goto SlGhY;
			fffbH:
			if (!(isset($IcHn3["\x66\x69\154\164\x65\x72\137\x69\x6d\x61\x67\145"]) && !is_null($IcHn3["\x66\x69\154\x74\x65\162\137\x69\x6d\x61\147\x65"]))) {
				goto J3PTm;
			}
			goto LsTZ0;
			XFy9F: mXw6_:
			goto d7n2j;
			UpIOZ: jWx8k:
			goto O2XXc;
			GPfen: i22Lm:
			goto TsHlL;
			ISfrX: scVhP:
			goto nXZDp;
			nXZDp:
			if (empty($IcHn3["\x66\151\154\x74\x65\x72\137\x6d\x6f\144\145\154"])) {
				goto zyZ1h;
			}
			goto k6A4h;
			PKyvT:
			if (empty($IcHn3["\146\151\x6c\x74\x65\162\137\x73\x6b\165"])) {
				goto mXw6_;
			}
			goto q5sYZ;
			wuA8s:
			$ZpMLl .= "\40\101\116\104\40\x70\62\143\x2e\x63\x61\164\x65\x67\157\x72\171\x5f\x69\x64\40\111\116\x20\x28" . $this->db->escape($IcHn3["\x66\x69\x6c\x74\x65\x72\137\x63\141\164\x65\147\157\x72\171"]) . "\x29";
			goto GPfen;
			rGUVz:
			if (!(isset($IcHn3["\146\x69\154\164\x65\162\x5f\x71\165\x61\x6e\x74\151\x74\171"]) && $IcHn3["\146\x69\x6c\164\x65\162\137\x71\165\x61\156\x74\x69\x74\x79"] !== '')) {
				goto CXWEv;
			}
			goto s5afu;
			zRyRf:
			if (!(!empty($IcHn3["\146\x69\x6c\x74\x65\162\137\155\141\156\165\146\x61\143\164\x75\162\x65\162"]) || $IcHn3["\146\151\154\164\x65\162\x5f\x6d\141\x6e\x75\x66\141\143\x74\x75\x72\x65\x72"] == "\60")) {
				goto spZUZ;
			}
			goto ohlI0;
			MG6oW: VjFPs:
			goto hf4vI;
			u3rv3: spZUZ:
			goto MG6oW;
			Cb8cG: J3PTm:
			goto PKyvT;
			TsHlL:
			if (empty($IcHn3["\146\x69\154\x74\x65\162\137\x6e\141\155\x65"])) {
				goto scVhP;
			}
			goto n5vI0;
			AM9ip: rsSok:
			goto zuOuk;
			dbD_k:
			if (empty($IcHn3["\x66\151\154\164\145\162\137\x70\x72\x6f\144\165\143\164\137\x69\144"])) {
				goto Y7tZ9;
			}
			goto xUu4j;
			hf4vI:
			if (empty($IcHn3["\146\x69\154\x74\x65\x72\x5f\143\x61\164\145\x67\x6f\162\171"])) {
				goto i22Lm;
			}
			goto wuA8s;
			SlGhY: dvyJg:
			goto Cb8cG;
			b_p_B:
			if (!(isset($IcHn3["\x66\151\x6c\x74\x65\x72\x5f\163\164\141\164\x75\163"]) && $IcHn3["\x66\151\x6c\164\x65\x72\137\163\x74\x61\x74\x75\163"] !== '')) {
				goto jWx8k;
			}
			goto kmf9P;
			LsTZ0:
			if ($IcHn3["\146\151\154\x74\x65\x72\x5f\x69\x6d\141\x67\145"] == 1) {
				goto o5clD;
			}
			goto DBZQ3;
			gxeAn: u5aIp:
			goto rGUVz;
			q5sYZ:
			$ZpMLl .= "\x20\x41\x4e\x44\40\160\x2e\163\153\x75\40\x4c\x49\113\x45\x20\x27\x25" . $this->db->escape($IcHn3["\146\x69\x6c\164\145\162\137\x73\153\x75"]) . "\45\47";
			goto XFy9F;
			Mb_Ay:
			if (empty($IcHn3["\146\x69\154\164\x65\x72\137\160\x72\x69\x63\x65"])) {
				goto u5aIp;
			}
			goto ecrUr;
			ecrUr:
			$ZpMLl .= "\x20\x41\x4e\104\40\160\56\x70\x72\151\x63\x65\x20\114\111\x4b\x45\40\47" . $this->db->escape($IcHn3["\x66\151\154\164\x65\162\x5f\160\x72\x69\x63\x65"]) . "\45\x27";
			goto gxeAn;
			fnVIF:
			$ZpMLl = "\x55\120\104\x41\x54\x45\x20" . DB_PREFIX . "\160\162\x6f\144\165\x63\x74\x20\x70\40\114\105\x46\x54\40\112\117\111\116\x20" . DB_PREFIX . "\x70\x72\x6f\144\x75\x63\x74\137\144\x65\x73\143\x72\x69\160\x74\151\x6f\156\x20\160\x64\40\117\x4e\40\50\160\x2e\x70\162\157\144\165\x63\164\x5f\x69\x64\x20\x3d\40\x70\x64\56\160\x72\x6f\x64\165\x63\x74\137\x69\144\51";
			goto iJcSD;
			d7n2j:
			if (!isset($IcHn3["\146\151\x6c\164\x65\x72\x5f\x6d\x61\x6e\165\146\x61\143\164\x75\162\145\162"])) {
				goto VjFPs;
			}
			goto zRyRf;
			zuOuk:
			$ZpMLl .= "\40\123\x45\124\x20\160\56\x73\x74\141\164\x75\163\x20\x3d\x20\x27" . (int)$IcHn3["\x66\151\154\x74\x65\x72\x5f\x73\x74\141\x74\165\163\x5f\141\x6c\x6c"] . "\47";
			goto Pe2j4;
			kmf9P:
			$ZpMLl .= "\40\101\116\104\40\x70\56\x73\x74\141\x74\x75\163\x20\75\x20\47" . (int)$IcHn3["\x66\151\154\x74\145\x72\x5f\163\164\141\164\x75\163"] . "\x27";
			goto UpIOZ;
			xWmYK:
			$ZpMLl .= "\x20\x4c\x45\106\124\40\112\x4f\x49\116\x20" . DB_PREFIX . "\160\162\x6f\x64\x75\x63\x74\137\x74\157\137\x63\x61\x74\x65\x67\x6f\162\x79\x20\160\62\143\x20\117\x4e\x20\50\x70\56\160\162\157\x64\x75\143\164\x5f\x69\x64\40\75\x20\160\x32\x63\56\x70\x72\157\x64\165\143\164\x5f\151\x64\x29";
			goto AM9ip;
			k6A4h:
			$ZpMLl .= "\x20\101\x4e\x44\x20\160\x2e\155\157\x64\x65\x6c\x20\114\111\113\x45\x20\x27\x25" . $this->db->escape($IcHn3["\146\151\x6c\164\145\162\137\x6d\157\144\145\x6c"]) . "\45\x27";
			goto VBy0j;
			VBy0j: zyZ1h:
			goto Mb_Ay;
			Ke0k0:
			goto dvyJg;
			goto ulSWi;
			M0gzN: Y7tZ9:
			goto fffbH;
			iJcSD:
			if (empty($IcHn3["\x66\151\x6c\x74\x65\162\x5f\143\141\x74\145\147\x6f\162\x79"])) {
				goto rsSok;
			}
			goto xWmYK;
			fv6Y4: CXWEv:
			goto b_p_B;
			n5vI0:
			$ZpMLl .= "\x20\x41\x4e\x44\40\x70\144\x2e\x6e\141\x6d\145\40\114\x49\113\x45\x20\x27\45" . $this->db->escape($IcHn3["\146\x69\154\164\x65\x72\137\x6e\141\x6d\x65"]) . "\45\x27";
			goto ISfrX;
			s5afu:
			$ZpMLl .= "\x20\x41\x4e\104\40\160\56\161\165\141\156\164\151\164\x79\40\75\40\x27" . (int)$IcHn3["\146\x69\x6c\x74\x65\162\137\x71\x75\x61\156\164\x69\x74\171"] . "\47";
			goto fv6Y4;
			O2XXc:
			$this->db->query($ZpMLl);
			goto BkFZL;
			ohlI0:
			$ZpMLl .= "\40\x41\116\104\40\x70\x2e\x6d\x61\x6e\165\146\x61\143\164\x75\x72\145\162\x5f\151\x64\40\111\116\x20\50" . $this->db->escape($IcHn3["\x66\151\x6c\x74\145\x72\x5f\x6d\141\x6e\x75\146\141\x63\x74\x75\162\x65\x72"]) . "\51";
			goto u3rv3;
			DBZQ3:
			$ZpMLl .= "\x20\x41\116\104\x20\50\160\x2e\151\x6d\x61\x67\x65\40\x49\123\40\x4e\x55\x4c\114\x20\x4f\122\40\x70\x2e\x69\x6d\141\x67\x65\x20\x3d\40\x27\47\x20\x4f\x52\40\x70\56\x69\x6d\141\x67\x65\x20\75\x20\47\156\x6f\137\x69\x6d\x61\x67\x65\x2e\160\x6e\147\x27\x29";
			goto Ke0k0;
			BkFZL:
		}

		public function _gPI($mUGCH)
		{
			$p0lxb = $this->db->query("\12\x20\40\40\x20\x20\x20\x20\40\40\40\x20\40\123\x45\114\105\103\124\x20\x60\x69\155\x61\x67\145\140\x20\x46\122\117\115\40\140" . DB_PREFIX . "\160\162\157\144\x75\x63\x74\140\12\x20\x20\x20\x20\x20\x20\x20\x20\x20\40\40\40\x20\40\40\40\127\110\105\x52\105\40\160\162\157\x64\165\143\164\137\x69\x64\x20\x3d\x20\47" . (int)$mUGCH . "\47\12\x20\x20\x20\40\x20\x20\x20\x20\40\x20\40\40");
			return $p0lxb->row["\151\x6d\141\x67\x65"];
		}

		public function _gPM($mUGCH)
		{
			$p0lxb = $this->db->query("\12\x20\x20\40\x20\x20\x20\40\x20\40\40\40\40\x53\x45\114\x45\x43\x54\40\x60\x6d\157\x64\145\154\x60\40\x46\x52\x4f\x4d\40\140" . DB_PREFIX . "\x70\x72\x6f\144\165\x63\164\140\12\x20\x20\40\x20\x20\40\40\x20\40\x20\x20\40\x20\x20\40\40\x57\x48\105\x52\x45\x20\x60\160\x72\157\x64\165\x63\164\137\151\x64\x60\40\75\40\x27" . (int)$mUGCH . "\47\12\40\x20\40\40\x20\40\x20\x20\40\x20\x20\40");
			return $p0lxb->row["\155\x6f\x64\145\x6c"];
		}

		public function _gPS($mUGCH)
		{
			$p0lxb = $this->db->query("\12\x20\40\40\x20\x20\x20\x20\40\40\x20\x20\40\123\x45\114\x45\103\x54\40\x60\163\x6b\x75\x60\x20\x46\x52\x4f\x4d\x20\140" . DB_PREFIX . "\x70\x72\x6f\x64\x75\x63\164\140\12\40\40\40\x20\x20\x20\x20\40\40\x20\40\x20\x20\40\x20\40\127\x48\105\122\x45\40\160\x72\157\144\x75\x63\x74\137\151\x64\40\75\x20\47" . (int)$mUGCH . "\47\xa\x20\40\40\40\40\40\40\x20\x20\40\40\x20");
			return $p0lxb->row["\163\x6b\165"];
		}

		public function _gPMID($mUGCH)
		{
			$p0lxb = $this->db->query("\12\40\40\40\40\40\40\x20\40\40\x20\40\40\123\105\x4c\x45\103\124\40\x60\155\141\156\165\146\x61\143\164\165\x72\x65\162\137\x69\x64\140\x20\106\122\117\x4d\x20\x60" . DB_PREFIX . "\160\162\157\x64\x75\143\164\140\12\x20\x20\40\x20\40\40\40\40\40\x20\x20\40\40\40\40\40\127\x48\x45\122\x45\40\140\x70\162\157\144\x75\143\164\137\x69\x64\x60\x20\x3d\x20\x27" . (int)$mUGCH . "\x27\12\40\x20\x20\x20\x20\x20\40\x20\40\40\x20\x20");
			return $p0lxb->row["\155\141\x6e\x75\x66\x61\143\164\x75\x72\x65\162\x5f\151\144"];
		}

		public function _gPC($mUGCH)
		{
			$dIv9t = $this->db->query("\12\x20\x20\40\x20\40\40\x20\x20\40\x20\x20\x20\x53\105\114\x45\x43\x54\x20\52\40\x46\122\x4f\115\x20\140" . DB_PREFIX . "\x70\x72\157\x64\165\143\x74\137\x74\x6f\137\x63\x61\x74\145\x67\x6f\x72\x79\x60\xa\40\40\x20\40\x20\40\x20\x20\x20\40\40\40\40\x20\x20\x20\127\x48\x45\x52\105\40\160\162\157\x64\165\x63\x74\x5f\x69\144\40\x3d\40\x27" . (int)$mUGCH . "\x27\xa\40\40\x20\40\40\x20\40\x20\x20\40\40\40");
			return $dIv9t->rows;
		}

		public function _gPMCID($mUGCH)
		{
			$dIv9t = $this->db->query("\12\x20\x20\40\x20\40\x20\x20\40\40\x20\x20\x20\x53\105\x4c\x45\x43\x54\x20\x60\x63\141\164\x65\147\157\x72\x79\x5f\151\144\x60\40\x46\122\117\x4d\x20\x60" . DB_PREFIX . "\160\x72\x6f\x64\x75\x63\x74\x5f\x74\x6f\x5f\143\141\x74\x65\x67\x6f\x72\x79\140\xa\40\x20\x20\40\40\x20\x20\x20\x20\40\x20\x20\x20\x20\x20\40\127\x48\x45\x52\x45\x20\x60\160\162\x6f\x64\x75\143\x74\137\151\x64\x60\40\x20\x20\40\x20\40\x3d\x20\47" . (int)$mUGCH . "\47\xa\x20\40\40\40\x20\40\40\40\40\x20\x20\40\40\x20\x20\x20\101\x4e\x44\40\x60\x6d\141\x69\156\137\143\x61\164\x65\147\157\162\x79\140\40\40\40\40\x20\x3d\x20\47\x31\47\12\40\x20\40\40\40\40\x20\40\x20\40\40\x20\x20\x20\x20\40\114\x49\115\111\124\40\x31\xa\x20\40\x20\40\40\x20\40\x20\x20\x20\40\x20");
			return $dIv9t->num_rows ? (int)$dIv9t->row["\143\x61\x74\145\147\x6f\162\171\x5f\151\144"] : 0;
		}

		public function _cPQ($mUGCH, $VrTbn)
		{
			$this->db->query("\12\40\x20\x20\x20\x20\x20\40\40\40\40\40\40\x55\x50\x44\101\124\x45\x20\140" . DB_PREFIX . "\x70\x72\157\x64\165\x63\x74\140\xa\40\x20\40\40\40\x20\40\x20\40\x20\x20\x20\40\x20\x20\40\123\105\124\x20\140\161\165\141\156\x74\151\164\171\140\x20\x20\x20\40\x20\40\x3d\40\47" . (int)$VrTbn . "\47\12\x20\x20\x20\40\40\40\40\40\40\40\40\40\40\x20\x20\40\127\x48\105\x52\x45\40\x60\x70\x72\157\144\x75\x63\164\x5f\x69\144\x60\40\40\x3d\40\47" . (int)$mUGCH . "\47\xa\40\x20\x20\x20\40\x20\40\40\x20\40\x20\40");
		}

		public function dL()
		{
			goto fI8lo;
			fI8lo:
			$this->db->query("\xa\40\x20\40\x20\x20\x20\40\40\40\x20\40\x20\104\x45\114\105\x54\105\40\x46\x52\117\115\x20\140" . DB_PREFIX . "\163\145\164\x74\151\x6e\x67\x60\12\x20\x20\40\40\x20\40\x20\40\x20\40\40\40\x20\40\x20\40\x57\110\105\x52\105\40\x60\143\157\x64\145\x60\40\x3d\x20\47\141\x70\145\x5f\146\x69\x6c\x74\145\x72\x27\xa\x20\40\40\40\40\x20\x20\x20\40\x20\x20\x20");
			goto c8Pq6;
			IQdAL: utPHa:
			goto W3LNv;
			c8Pq6:
			if (!file_exists(DIR_SYSTEM . "\141\160\x65\137\146\x69\154\164\x65\x72\56\x6c\x69\143")) {
				goto utPHa;
			}
			goto Lng01;
			Lng01:
			unlink(DIR_SYSTEM . "\141\160\x65\137\146\151\x6c\x74\145\162\x2e\154\x69\143");
			goto IQdAL;
			W3LNv:
		}

		public function __construct($dnQUF)
		{
			parent::__construct($dnQUF);
		}

		public function checkLicense($WFaCx)
		{
			goto rvfS2;
			Al9RM:
			return true;
			goto wAh5q;
			tRj4M:
			return false;
			goto dbjGe;
			CFtY9:
			if (isset($lbNU0["\x65\162\162\157\162"])) {
				goto Yg1Ey;
			}
			goto Al9RM;
			wAh5q: Yg1Ey:
			goto tRj4M;
			rvfS2:
			$lbNU0 = $this->validateLicense($WFaCx);
			goto CFtY9;
			dbjGe:
		}
	}
